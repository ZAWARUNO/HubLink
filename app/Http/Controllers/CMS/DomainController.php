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
        $domain = Auth::user()->domain;
        // If user doesn't have a domain, create a default one
        if (!$domain) {
            return view('cms.pages.domain-setup');
        }
        // If user already has a domain, show edit form with existing data
        return view('cms.pages.domain-setup', compact('domain'));
    }

    public function showEditForm()
    {
        $domain = Auth::user()->domain;
        // If user doesn't have a domain, redirect to setup
        if (!$domain) {
            return redirect()->route('cms.domain.setup');
        }
        return view('cms.pages.domain-setup', compact('domain'));
    }

    public function store(Request $request)
    {
        // Check if user already has a domain
        $existingDomain = Auth::user()->domain;
        
        if ($existingDomain) {
            // Update existing domain instead of creating new one
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

        // If no existing domain, create new one (original behavior)
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
        $domain = Auth::user()->domain;
        
        // If user doesn't have a domain, redirect to setup
        if (!$domain) {
            return redirect()->route('cms.domain.setup');
        }

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
