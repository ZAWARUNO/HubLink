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
        return view('cms.pages.domain-setup');
    }

    public function store(Request $request)
    {
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
}
