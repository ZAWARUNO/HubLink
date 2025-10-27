@extends('cms.layouts.app-mobile')

@section('content')
<style>
    /* Bottom Sheet Styles */
    .bottom-sheet {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: white;
        border-radius: 20px 20px 0 0;
        box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.15);
        transform: translateY(100%);
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 1000;
        max-height: 85vh;
        display: flex;
        flex-direction: column;
    }
    
    .bottom-sheet.active {
        transform: translateY(0);
    }
    
    .bottom-sheet-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s, visibility 0.3s;
        z-index: 999;
    }
    
    .bottom-sheet-overlay.active {
        opacity: 1;
        visibility: visible;
    }
    
    /* FAB */
    .fab {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: #00c499;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(0, 196, 153, 0.4);
        z-index: 100;
        border: none;
        cursor: pointer;
    }
    
    .component-wrapper {
        touch-action: none;
    }
    
    .component-actions {
        position: absolute;
        top: 8px;
        right: 8px;
        display: flex;
        gap: 4px;
        z-index: 10;
    }
    
    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: white;
        display: flex;
        align-items: center;
        justify-center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }
</style>

<!-- Top Bar -->
<div class="bg-white border-b sticky top-0 z-50">
    <div class="flex items-center justify-between px-4 py-3">
        <div class="flex items-center gap-3">
            <a href="{{ route('cms.home') }}" class="p-2">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-lg font-bold text-gray-900">Mobile Builder</h1>
                <p class="text-xs text-gray-500">{{ $domain->slug }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('cms.builder.show', ['domainId' => $domain->id]) }}" class="px-2 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg" title="Desktop View">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </a>
            <button onclick="previewPage()" class="px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
            </button>
            <button onclick="publishPage()" class="px-4 py-2 bg-primary text-white text-sm font-medium rounded-lg">
                Publish
            </button>
        </div>
    </div>
</div>

<!-- Canvas Area -->
<div class="p-4 pb-24">
    <div id="canvas" class="space-y-3 bg-white rounded-2xl shadow-sm p-4 min-h-[400px]">
        @if($components->count() == 0)
            <div id="empty-canvas-message" class="text-center py-16 text-gray-500">
                <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <h3 class="text-base font-medium text-gray-900 mb-1">Belum ada komponen</h3>
                <p class="text-sm text-gray-500">Tap tombol + untuk menambah</p>
            </div>
        @endif
        
        @foreach($components as $component)
            <div class="component-wrapper relative group border-2 border-transparent hover:border-dashed hover:border-primary rounded-lg p-3 transition-all" 
                 data-id="{{ $component->id }}" 
                 data-type="{{ $component->type }}"
                 data-properties="{{ json_encode($component->properties) }}">
                
                <!-- Component Actions -->
                <div class="component-actions">
                    <button onclick="editComponent({{ $component->id }})" class="action-btn text-blue-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </button>
                    <button onclick="deleteComponent({{ $component->id }})" class="action-btn text-red-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Component Content -->
                <div class="component-content pointer-events-none">
                    @switch($component->type)
                        @case('text')
                            <div class="text-{{ $component->properties['alignment'] ?? 'left' }} {{ $component->properties['size'] ?? 'text-base' }}">
                                {!! $component->properties['content'] ?? 'Text content' !!}
                            </div>
                            @break
                        
                        @case('button')
                            <a href="{{ $component->properties['url'] ?? '#' }}" 
                               class="block text-center px-6 py-3 rounded-lg"
                               style="background-color: {{ $component->properties['backgroundColor'] ?? '#00c499' }}; color: {{ $component->properties['textColor'] ?? '#ffffff' }};">
                                {{ $component->properties['text'] ?? 'Button' }}
                            </a>
                            @break
                        
                        @case('image')
                            <div class="w-full">
                                <img src="{{ $component->properties['src'] ?? 'https://placehold.co/400x200' }}" 
                                     alt="{{ $component->properties['alt'] ?? 'Image' }}" 
                                     class="w-full h-auto rounded">
                            </div>
                            @break
                        
                        @case('link')
                            <a href="{{ $component->properties['url'] ?? '#' }}" 
                               style="color: {{ $component->properties['textColor'] ?? '#00c499' }};">
                                {{ $component->properties['text'] ?? 'Link text' }}
                            </a>
                            @break
                        
                        @case('divider')
                            <hr class="border-gray-300">
                            @break
                        
                        @case('profile')
                            <div class="flex items-center gap-4 p-4">
                                @if($domain->user->profile_photo)
                                    <img src="{{ asset('storage/' . $domain->user->profile_photo) }}" 
                                         alt="{{ $domain->user->name }}" 
                                         class="w-16 h-16 rounded-full object-cover">
                                @else
                                    <div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <h3 class="font-semibold text-lg text-gray-900">{{ $domain->user->name }}</h3>
                                    <p class="text-gray-600 text-sm">{{ '@' . $domain->slug }}</p>
                                </div>
                            </div>
                            @break
                        
                        @case('template')
                            <div class="rounded-lg overflow-hidden shadow-lg bg-white">
                                <img class="w-full h-40 object-cover" src="{{ $component->properties['image'] ?? 'https://placehold.co/400x300' }}" alt="{{ $component->properties['title'] ?? 'Template' }}">
                                <div class="p-4">
                                    <div class="font-bold text-lg mb-2">{{ $component->properties['title'] ?? 'Template Title' }}</div>
                                    <p class="text-gray-700 text-sm">{{ $component->properties['description'] ?? 'Description' }}</p>
                                </div>
                                <div class="px-4 pb-4 flex justify-between items-center">
                                    <span class="text-lg font-bold text-primary">Rp {{ number_format($component->properties['price'] ?? 0, 0, ',', '.') }}</span>
                                    <button class="bg-primary text-white font-bold py-2 px-4 rounded text-sm">
                                        {{ $component->properties['buttonText'] ?? 'Buy Now' }}
                                    </button>
                                </div>
                            </div>
                            @break
                    @endswitch
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- FAB -->
<button onclick="openComponentsSheet()" class="fab">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
    </svg>
</button>

<!-- Bottom Sheet Overlay -->
<div id="bottom-sheet-overlay" class="bottom-sheet-overlay" onclick="closeBottomSheet()"></div>

<!-- Components Bottom Sheet -->
<div id="components-sheet" class="bottom-sheet">
    <div class="w-10 h-1 bg-gray-300 rounded-full mx-auto my-3"></div>
    <div class="px-4 py-3 border-b flex justify-between items-center">
        <h2 class="text-lg font-semibold text-gray-900">Tambah Komponen</h2>
        <button onclick="closeBottomSheet()" class="p-2 text-gray-500">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
    <div class="flex-1 overflow-y-auto p-4 space-y-2">
        @php
            $componentIcons = [
                'text' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 00-2 2v9a2 2 0 002 2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>',
                'button' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path>',
                'image' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>',
                'link' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>',
                'divider' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5v-4m0 0h-4m4 0l-5-5"></path>',
                'profile' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>',
                'template' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>'
            ];
        @endphp
        @foreach(['text' => 'Text', 'button' => 'Button', 'image' => 'Image', 'link' => 'Link', 'divider' => 'Divider', 'profile' => 'Profile', 'template' => 'Template'] as $type => $label)
        <button onclick="addComponent('{{ $type }}')" class="w-full p-4 border-2 border-gray-200 rounded-xl hover:border-primary hover:bg-primary/5 flex items-center gap-3 transition-all">
            <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    {!! $componentIcons[$type] !!}
                </svg>
            </div>
            <div class="text-left">
                <div class="font-medium text-gray-900">{{ $label }}</div>
            </div>
        </button>
        @endforeach
    </div>
</div>

<!-- Properties Bottom Sheet -->
<div id="properties-sheet" class="bottom-sheet">
    <div class="w-10 h-1 bg-gray-300 rounded-full mx-auto my-3"></div>
    <div class="px-4 py-3 border-b flex justify-between items-center">
        <h2 class="text-lg font-semibold text-gray-900">Edit Komponen</h2>
        <button onclick="closeBottomSheet()" class="p-2 text-gray-500">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
    <div class="flex-1 overflow-y-auto p-4" id="properties-content">
        <!-- Properties will be loaded here -->
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/builder-mobile.js') }}"></script>
<script>
    const domainId = {{ $domain->id }};
    const domainUser = {
        name: '{{ $domain->user->name }}',
        profilePhoto: '{{ $domain->user->profile_photo ? asset("storage/" . $domain->user->profile_photo) : "" }}',
        slug: '{{ $domain->slug }}'
    };
</script>
@endsection
