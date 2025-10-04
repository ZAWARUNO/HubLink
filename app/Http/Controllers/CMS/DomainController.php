<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DomainController extends Controller
{
    public function showSetupForm()
    {
        $domains = Auth::user()->domains;
        // If user doesn't have any domains, show setup form
        if ($domains->count() == 0) {
            return view('cms.pages.domain-setup');
        }
        // If user already has domains, show the first one for editing
        $domain = $domains->first();
        return view('cms.pages.domain-setup', compact('domain'));
    }

    public function showEditForm()
    {
        $domains = Auth::user()->domains;
        // If user doesn't have any domains, redirect to setup
        if ($domains->count() == 0) {
            return redirect()->route('cms.domain.setup');
        }
        // Show the first domain for editing
        $domain = $domains->first();
        return view('cms.pages.domain-setup', compact('domain'));
    }

    public function store(Request $request)
    {
        // Check if user already has domains
        $existingDomains = Auth::user()->domains;
        
        if ($existingDomains->count() > 0) {
            // Update the first domain instead of creating new one
            $existingDomain = $existingDomains->first();
            
            $request->validate([
                'slug' => ['required', 'alpha_dash', 'min:3', 'max:30', 'unique:domains,slug,' . $existingDomain->id],
                'title' => ['nullable', 'string', 'max:100'],
                'bio' => ['nullable', 'string', 'max:300'],
            ], [
                'slug.unique' => 'Nama domain sudah dipakai, silakan gunakan yang lain.',
            ]);

            $slug = Str::of($request->input('slug'))->lower()->slug('-');

            $existingDomain->update([
                'slug' => $slug,
                'title' => $request->input('title'),
                'bio' => $request->input('bio'),
            ]);

            return redirect()->route('cms.home')->with('status', 'Domain berhasil diperbarui: ' . $existingDomain->slug);
        }

        // If no existing domains, create new one (original behavior)
        $request->validate([
            'slug' => ['required', 'alpha_dash', 'min:3', 'max:30', 'unique:domains,slug'],
            'title' => ['nullable', 'string', 'max:100'],
            'bio' => ['nullable', 'string', 'max:300'],
        ], [
            'slug.unique' => 'Nama domain sudah dipakai, silakan gunakan yang lain.',
        ]);

        $slug = Str::of($request->input('slug'))->lower()->slug('-');

        $domain = Domain::create([
            'user_id' => Auth::id(),
            'slug' => $slug,
            'title' => $request->input('title'),
            'bio' => $request->input('bio'),
            'settings' => [
                'theme' => 'default',
                'accent' => '#00c499',
            ],
        ]);

        return redirect()->route('cms.home')->with('status', 'Domain berhasil dibuat: ' . $domain->slug);
    }

    public function update(Request $request)
    {
        $domains = Auth::user()->domains;
        
        // If user doesn't have any domains, redirect to setup
        if ($domains->count() == 0) {
            return redirect()->route('cms.domain.setup');
        }

        $domain = $domains->first();
        
        $request->validate([
            'slug' => ['required', 'alpha_dash', 'min:3', 'max:30', 'unique:domains,slug,' . $domain->id],
            'title' => ['nullable', 'string', 'max:100'],
            'bio' => ['nullable', 'string', 'max:300'],
        ], [
            'slug.unique' => 'Nama domain sudah dipakai, silakan gunakan yang lain.',
        ]);

        $slug = Str::of($request->input('slug'))->lower()->slug('-');

        $domain->update([
            'slug' => $slug,
            'title' => $request->input('title'),
            'bio' => $request->input('bio'),
        ]);

        return redirect()->route('cms.home')->with('status', 'Domain berhasil diperbarui: ' . $domain->slug);
    }
}