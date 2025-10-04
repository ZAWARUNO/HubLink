@extends('cms.layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Page Builder</h1>
    </div>
    
    @if($domains->count() > 0)
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($domains as $domain)
                <div class="bg-white rounded-2xl border p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $domain->title ?? ucfirst($domain->slug) }}</h3>
                            <p class="text-sm text-gray-500">hub.link/{{ $domain->slug }}</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('cms.builder.show', $domain->id) }}" class="flex-1 bg-primary hover:bg-primary-dark text-white text-center py-2 rounded-lg">
                            Edit Page
                        </a>
                        <a href="{{ url('/'.$domain->slug) }}" target="_blank" class="px-4 py-2 border rounded-lg">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-2xl border p-8 text-center">
            <div class="mx-auto w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No domains found</h3>
            <p class="text-gray-500 mb-4">You need to create a domain before you can use the page builder.</p>
            <a href="{{ route('cms.domain.setup') }}" class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-dark text-white rounded-lg">
                Create Domain
            </a>
        </div>
    @endif
</div>
@endsection