<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Domain;
use App\Models\Visitor;
use Illuminate\Support\Str;

class TrackVisitor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Hanya track untuk halaman frontend (bukan CMS)
        if (!$request->is('cms/*') && !$request->is('login') && !$request->is('register')) {
            $this->trackVisitor($request);
        }

        return $next($request);
    }

    /**
     * Track visitor
     */
    private function trackVisitor(Request $request)
    {
        try {
            // Ambil slug dari URL (format: /{slug} atau /{slug}/*)
            $slug = $request->segment(1);
            
            if (!$slug) {
                return;
            }

            // Cari domain berdasarkan slug
            $domain = Domain::where('slug', $slug)->first();
            
            if (!$domain) {
                return;
            }

            // Generate atau ambil session ID
            $sessionId = session()->getId();
            if (!$sessionId) {
                $sessionId = Str::uuid()->toString();
                session()->setId($sessionId);
                session()->start();
            }

            // Cek apakah visitor dengan session ini sudah ada hari ini
            $existingVisitor = Visitor::where('session_id', $sessionId)
                ->where('domain_id', $domain->id)
                ->whereDate('visited_at', today())
                ->first();

            // Jika belum ada, catat sebagai visitor baru
            if (!$existingVisitor) {
                Visitor::create([
                    'domain_id' => $domain->id,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'session_id' => $sessionId,
                    'page_url' => $request->fullUrl(),
                    'referrer' => $request->header('referer'),
                    'visited_at' => now(),
                ]);
            }
        } catch (\Exception $e) {
            // Silent fail - jangan ganggu user experience
            \Log::error('Visitor tracking error: ' . $e->getMessage());
        }
    }
}
