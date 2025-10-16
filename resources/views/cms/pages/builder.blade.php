@extends('cms.layouts.app')

@section('content')
@push('scripts')
    <script src="{{ asset('js/image-resize.js') }}"></script>
@endpush

<style>
    .sortable-ghost {
        opacity: 0.5;
        background-color: #f0f9ff;
        border: 1px dashed #3b82f6;
    }
    
    .sortable-drag {
        opacity: 0.8;
        background-color: #dbeafe;
        border: 1px solid #3b82f6;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    
    .sortable-chosen {
        background-color: #eff6ff;
        border: 1px solid #3b82f6;
    }
    
    /* Ensure canvas has proper styling for drag over */
    #canvas.drag-over {
        border: 2px dashed #3b82f6;
        background-color: #dbeafe;
        border-radius: 0.5rem;
    }
    
    /* Add specific styling for component items during drag */
    .component-item.dragging {
        opacity: 0.5;
    }
    
    /* Resizable image styles */
    .resizable-image-container {
        position: relative;
        display: inline-block;
        min-width: 50px;
        min-height: 50px;
    }
    
    .resizable-image-element {
        width: 100%;
        height: 100%;
        display: block;
    }
    
    .resize-handle {
        position: absolute;
        width: 10px;
        height: 10px;
        background-color: #3b82f6;
        border: 1px solid white;
        border-radius: 50%;
        z-index: 10;
        opacity: 0;
        transition: opacity 0.2s ease;
        pointer-events: auto;
    }
    
    .component-wrapper:hover .resize-handle,
    .resizable-image-container:hover .resize-handle,
    .resizing .resize-handle {
        opacity: 1;
    }
    
    .resize-handle-nw {
        top: -5px;
        left: -5px;
        cursor: nw-resize;
    }
    
    .resize-handle-ne {
        top: -5px;
        right: -5px;
        cursor: ne-resize;
    }
    
    .resize-handle-sw {
        bottom: -5px;
        left: -5px;
        cursor: sw-resize;
    }
    
    .resize-handle-se {
        bottom: -5px;
        right: -5px;
        cursor: se-resize;
    }
    
    .resize-handle-n {
        top: -5px;
        left: 50%;
        transform: translateX(-50%);
        cursor: n-resize;
    }
    
    .resize-handle-s {
        bottom: -5px;
        left: 50%;
        transform: translateX(-50%);
        cursor: s-resize;
    }
    
    .resize-handle-w {
        top: 50%;
        left: -5px;
        transform: translateY(-50%);
        cursor: w-resize;
    }
    
    .resize-handle-e {
        top: 50%;
        right: -5px;
        transform: translateY(-50%);
        cursor: e-resize;
    }
    
    /* Mobile Preview Mode */
    .canvas-container {
        transition: all 0.3s ease;
    }
    
    .canvas-container.mobile-view {
        max-width: 375px;
        margin: 0 auto;
        box-shadow: 0 0 0 8px #1f2937, 0 0 0 10px #374151;
        border-radius: 24px;
        overflow: hidden;
    }
    
    .canvas-container.mobile-view .bg-white {
        border-radius: 0 !important;
    }
    
    /* Mobile device frame */
    .mobile-frame {
        position: relative;
    }
    
    .mobile-frame::before {
        content: '';
        position: absolute;
        top: -30px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 6px;
        background: #374151;
        border-radius: 3px;
        z-index: 10;
    }
    
    /* Mobile Responsive Builder */
    @media (max-width: 768px) {
        /* Hide components panel by default on mobile */
        .components-panel {
            position: fixed;
            left: -100%;
            top: 0;
            bottom: 0;
            z-index: 50;
            transition: left 0.3s ease;
            width: 80%;
            max-width: 280px;
        }
        
        .components-panel.open {
            left: 0;
        }
        
        /* Hide properties panel by default on mobile */
        #properties-panel {
            position: fixed;
            right: -100%;
            top: 0;
            bottom: 0;
            z-index: 50;
            transition: right 0.3s ease;
            width: 90%;
            max-width: 320px;
        }
        
        #properties-panel:not(.hidden) {
            right: 0;
        }
        
        /* Full width canvas on mobile */
        .canvas-area {
            width: 100% !important;
        }
        
        /* Adjust header buttons for mobile */
        .builder-header {
            padding: 0.75rem;
        }
        
        .builder-header button span {
            display: none;
        }
        
        .builder-header button {
            padding: 0.5rem;
        }
        
        /* Mobile canvas container */
        .canvas-container {
            max-width: 100% !important;
            margin: 0 !important;
            padding: 1rem !important;
        }
        
        .canvas-container.mobile-view {
            max-width: 100% !important;
            box-shadow: none !important;
            border-radius: 0 !important;
        }
    }
    
    /* Overlay for mobile panels */
    .panel-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 40;
    }
    
    .panel-overlay.active {
        display: block;
    }
</style>

<div id="builder-app" class="flex flex-col h-full">
    <!-- Builder Header -->
    <div class="bg-white border-b p-4 flex justify-between items-center builder-header">
        <div class="flex items-center gap-3">
            <!-- Mobile Components Toggle -->
            <button onclick="toggleComponentsPanel()" class="md:hidden p-2 hover:bg-gray-100 rounded-lg">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            <div>
                <h1 class="text-xl font-bold text-gray-900">Page Builder</h1>
                <p class="text-gray-600 hidden sm:block">{{ $domain->title ?? 'hub.link/'.$domain->slug }}</p>
            </div>
        </div>
        <div class="flex gap-2 items-center">
            <!-- Device Preview Toggle -->
            <div class="flex border rounded-lg overflow-hidden">
                <button id="desktop-view-btn" onclick="toggleDeviceView('desktop')" class="px-3 py-2 bg-primary text-white flex items-center gap-2 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-sm">Desktop</span>
                </button>
                <button id="mobile-view-btn" onclick="toggleDeviceView('mobile')" class="px-3 py-2 bg-white hover:bg-gray-50 flex items-center gap-2 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-sm">Mobile</span>
                </button>
            </div>
            
            <button onclick="previewPage()" class="px-4 py-2 border rounded-lg flex items-center gap-2 hover:bg-gray-50">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                Preview
            </button>
            <button onclick="publishPage()" class="px-4 py-2 bg-primary hover:bg-primary-dark text-white rounded-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                </svg>
                Publish
            </button>
        </div>
    </div>

    <div class="flex flex-1 overflow-hidden">
        <!-- Components Panel -->
        <div class="w-64 bg-white border-r flex flex-col components-panel" id="components-panel">
            <div class="p-4 border-b flex justify-between items-center">
                <h2 class="font-semibold text-gray-900">Components</h2>
                <button onclick="toggleComponentsPanel()" class="md:hidden p-1 hover:bg-gray-100 rounded">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="flex-1 overflow-y-auto p-4 space-y-2">
                <div draggable="true" data-type="text" class="component-item p-3 border rounded-lg cursor-move hover:bg-gray-50 flex items-center gap-2 transition-colors duration-200">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 00-2 2v9a2 2 0 002 2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Text</span>
                </div>
                <div draggable="true" data-type="button" class="component-item p-3 border rounded-lg cursor-move hover:bg-gray-50 flex items-center gap-2 transition-colors duration-200">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path>
                    </svg>
                    <span>Button</span>
                </div>
                <div draggable="true" data-type="image" class="component-item p-3 border rounded-lg cursor-move hover:bg-gray-50 flex items-center gap-2 transition-colors duration-200">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>Image</span>
                </div>
                <div draggable="true" data-type="link" class="component-item p-3 border rounded-lg cursor-move hover:bg-gray-50 flex items-center gap-2 transition-colors duration-200">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                    </svg>
                    <span>Link</span>
                </div>
                <div draggable="true" data-type="divider" class="component-item p-3 border rounded-lg cursor-move hover:bg-gray-50 flex items-center gap-2 transition-colors duration-200">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5v-4m0 0h-4m4 0l-5-5"></path>
                    </svg>
                    <span>Divider</span>
                </div>
                <div draggable="true" data-type="profile" class="component-item p-3 border rounded-lg cursor-move hover:bg-gray-50 flex items-center gap-2 transition-colors duration-200">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span>Profile</span>
                </div>
                <div draggable="true" data-type="template" class="component-item p-3 border rounded-lg cursor-move hover:bg-gray-50 flex items-center gap-2 transition-colors duration-200">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <span>Template</span>
                </div>
            </div>
        </div>

        <!-- Canvas Area -->
        <div class="flex-1 flex flex-col canvas-area">
            <div class="flex-1 overflow-auto p-4 md:p-8 bg-gray-50">
                <div id="canvas-container" class="canvas-container max-w-2xl mx-auto bg-white min-h-full shadow-sm border rounded-lg p-6">
                    <div id="canvas" class="space-y-4">
                        <!-- Components will be added here -->
                        @if($components->count() == 0)
                            <div id="empty-canvas-message" class="text-center py-12 text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No components yet</h3>
                                <p class="mt-1 text-sm text-gray-500">Drag components here from the left panel</p>
                            </div>
                        @endif
                        @foreach($components as $component)
                            <div class="component-wrapper relative group border border-transparent hover:border-dashed hover:border-gray-300 rounded p-2" 
                                 data-id="{{ $component->id }}" 
                                 data-type="{{ $component->type }}"
                                 data-properties="{{ json_encode($component->properties) }}"
                                 @if($component->digital_product_path)
                                 data-digital-product-path="{{ $component->digital_product_path }}"
                                 @endif>
                                <div class="component-content">
                                    @switch($component->type)
                                        @case('text')
                                            @php
                                                $textClasses = '';
                                                if (isset($component->properties['alignment'])) {
                                                    $textClasses .= ' text-' . $component->properties['alignment'];
                                                }
                                                if (isset($component->properties['size'])) {
                                                    $textClasses .= ' ' . $component->properties['size'];
                                                }
                                                if (isset($component->properties['bold']) && $component->properties['bold']) {
                                                    $textClasses .= ' font-bold';
                                                }
                                                if (isset($component->properties['italic']) && $component->properties['italic']) {
                                                    $textClasses .= ' italic';
                                                }
                                                if (isset($component->properties['underline']) && $component->properties['underline']) {
                                                    $textClasses .= ' underline';
                                                }
                                            @endphp
                                            <div class="text-content{{ $textClasses }}">
                                                {!! $component->properties['content'] ?? 'Text content' !!}
                                            </div>
                                            @break
                                        @case('button')
                                            @php
                                                // Build button styles
                                                $buttonClasses = '';
                                                $buttonStyles = '';
                                                
                                                // Add padding classes
                                                if (isset($component->properties['padding'])) {
                                                    $buttonClasses .= ' ' . $component->properties['padding'];
                                                }
                                                
                                                // Add font size and weight classes
                                                if (isset($component->properties['fontSize'])) {
                                                    $buttonClasses .= ' ' . $component->properties['fontSize'];
                                                }
                                                if (isset($component->properties['fontWeight'])) {
                                                    $buttonClasses .= ' ' . $component->properties['fontWeight'];
                                                }
                                                
                                                // Add border radius classes
                                                if (isset($component->properties['borderRadius']) && $component->properties['borderRadius'] !== 'none') {
                                                    $buttonClasses .= ' ' . $component->properties['borderRadius'];
                                                }
                                                
                                                // Add border styles
                                                if (isset($component->properties['borderWidth']) && $component->properties['borderWidth'] !== '0') {
                                                    $buttonStyles .= 'border: ' . $component->properties['borderWidth'] . 'px solid ' . ($component->properties['borderColor'] ?? '#00c499') . ';';
                                                }
                                                
                                                // Add background and text color styles
                                                $buttonStyles .= 'background-color: ' . ($component->properties['backgroundColor'] ?? '#00c499') . ';';
                                                $buttonStyles .= 'color: ' . ($component->properties['textColor'] ?? '#ffffff') . ';';
                                            @endphp
                                            <a href="{{ $component->properties['url'] ?? '#' }}" 
                                               class="{{ trim($buttonClasses) }}"
                                               style="{{ $buttonStyles }}">
                                                {{ $component->properties['text'] ?? 'Button' }}
                                            </a>
                                            @break
                                        @case('link')
                                            @php
                                                // Build link styles
                                                $linkClasses = '';
                                                $linkStyles = '';
                                                
                                                // Add font size and weight classes
                                                if (isset($component->properties['fontSize'])) {
                                                    $linkClasses .= ' ' . $component->properties['fontSize'];
                                                }
                                                if (isset($component->properties['fontWeight'])) {
                                                    $linkClasses .= ' ' . $component->properties['fontWeight'];
                                                }
                                                
                                                // Add text decoration class
                                                if (isset($component->properties['textDecoration']) && $component->properties['textDecoration'] !== 'no-underline') {
                                                    $linkClasses .= ' ' . $component->properties['textDecoration'];
                                                } else if (isset($component->properties['textDecoration']) && $component->properties['textDecoration'] === 'no-underline') {
                                                    // Remove underline if explicitly set to no-underline
                                                    $linkClasses .= ' no-underline';
                                                }
                                                
                                                // Add text color style
                                                $linkStyles .= 'color: ' . ($component->properties['textColor'] ?? '#00c499') . ';';
                                            @endphp
                                            <a href="{{ $component->properties['url'] ?? '#' }}" 
                                               class="{{ trim($linkClasses) }}"
                                               style="{{ $linkStyles }}">
                                                {{ $component->properties['text'] ?? 'Link text' }}
                                            </a>
                                            @break
                                        @case('image')
                                            <div class="resizable-image-container" style="position: relative; display: inline-block; width: {{ $component->properties['width'] ?? '100%' }}; height: {{ $component->properties['height'] ?? 'auto' }};">
                                                <img src="{{ $component->properties['src'] ?? 'https://placehold.co/400x200' }}" 
                                                     alt="{{ $component->properties['alt'] ?? 'Image' }}" 
                                                     class="resizable-image-element" style="width: 100%; height: 100%; display: block;">
                                            </div>
                                            @break
                                        @case('divider')
                                            <hr class="border-gray-300">
                                            @break
                                        @case('profile')
                                            @php
                                                $showPhoto = $component->properties['showPhoto'] ?? true;
                                                $showName = $component->properties['showName'] ?? true;
                                                $showUsername = $component->properties['showUsername'] ?? true;
                                                $alignment = $component->properties['alignment'] ?? 'left';
                                                $layout = $component->properties['layout'] ?? 'horizontal';
                                                $photoSize = $component->properties['photoSize'] ?? 'medium';
                                                
                                                // Photo size mapping
                                                $sizeMap = [
                                                    'small' => 'w-12 h-12',
                                                    'medium' => 'w-16 h-16',
                                                    'large' => 'w-24 h-24',
                                                    'xlarge' => 'w-32 h-32'
                                                ];
                                                $photoSizeClass = $sizeMap[$photoSize] ?? 'w-16 h-16';
                                                
                                                // Alignment classes
                                                $alignmentClass = '';
                                                if ($alignment === 'center') {
                                                    $alignmentClass = 'justify-center text-center';
                                                } elseif ($alignment === 'right') {
                                                    $alignmentClass = 'justify-end text-right';
                                                } else {
                                                    $alignmentClass = 'justify-start text-left';
                                                }
                                                
                                                // Layout classes
                                                $layoutClass = 'flex items-center gap-4 p-4';
                                                if ($layout === 'vertical') {
                                                    $layoutClass = 'flex flex-col items-center gap-4 p-4 text-center';
                                                } elseif ($layout === 'compact') {
                                                    $layoutClass = 'flex items-center gap-2 p-2';
                                                } elseif ($layout === 'card') {
                                                    $layoutClass = 'flex flex-col items-center gap-4 p-6 bg-gray-50 rounded-lg border';
                                                }
                                            @endphp
                                            <div class="{{ $layoutClass }} {{ $alignmentClass }}">
                                                @if($showPhoto)
                                                    @if($domain->user->profile_photo)
                                                        <img src="{{ asset('storage/' . $domain->user->profile_photo) }}" 
                                                             alt="{{ $domain->user->name }}" 
                                                             class="{{ $photoSizeClass }} rounded-full object-cover">
                                                    @else
                                                        <div class="{{ $photoSizeClass }} rounded-full bg-primary/10 flex items-center justify-center">
                                                            <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                @endif
                                                @if($showName || $showUsername)
                                                    <div>
                                                        @if($showName)
                                                            <h3 class="font-semibold text-lg text-gray-900">{{ $domain->user->name }}</h3>
                                                        @endif
                                                        @if($showUsername)
                                                            <p class="text-gray-600 text-sm">{{ '@' . $domain->slug }}</p>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                            @break
                                        @case('template')
                                            @php
                                                $isEmpty = isset($component->properties['isEmpty']) && $component->properties['isEmpty'] === true;
                                            @endphp
                                            @if($isEmpty)
                                            <div class="max-w-sm rounded overflow-hidden shadow-lg bg-white border-2 border-dashed border-gray-300">
                                                <div class="bg-gray-100 h-48 flex items-center justify-center">
                                                    <div class="text-center p-4">
                                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                        </svg>
                                                        <h3 class="mt-2 text-sm font-medium text-gray-900">Empty Template</h3>
                                                        <p class="mt-1 text-sm text-gray-500">No product added</p>
                                                    </div>
                                                </div>
                                                <div class="px-6 py-4">
                                                    <div class="font-bold text-xl mb-2 text-gray-400">No Product</div>
                                                    <p class="text-gray-500 text-base">
                                                        <a href="/cms/products" class="text-primary hover:underline">Add products in the product page</a>
                                                    </p>
                                                </div>
                                                <div class="px-6 pt-4 pb-2 flex justify-between items-center">
                                                    <span class="text-xl font-bold text-primary">Rp 0</span>
                                                    <button class="bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded">
                                                        Add Product
                                                    </button>
                                                </div>
                                            </div>
                                            @else
                                            <div class="max-w-sm rounded overflow-hidden shadow-lg bg-white">
                                                <img class="w-full h-48 object-cover" src="{{ $component->properties['image'] ?? 'https://placehold.co/400x300' }}" alt="{{ $component->properties['title'] ?? 'Template Image' }}">
                                                <div class="px-6 py-4">
                                                    <div class="font-bold text-xl mb-2">{{ $component->properties['title'] ?? 'Template Title' }}</div>
                                                    <p class="text-gray-700 text-base">
                                                        {{ $component->properties['description'] ?? 'Template description goes here.' }}
                                                    </p>
                                                </div>
                                                <div class="px-6 pt-4 pb-2 flex justify-between items-center">
                                                    <span class="text-xl font-bold text-primary">Rp {{ number_format($component->properties['price'] ?? 0, 0, ',', '.') }}</span>
                                                    <a href="{{ route('checkout.show', ['domain' => $domain, 'componentId' => $component->id]) }}" class="bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded no-underline">
                                                        {{ $component->properties['buttonText'] ?? 'Buy Now' }}
                                                    </a>
                                                </div>
                                            </div>
                                            @endif
                                            @break
                                    @endswitch
                                </div>
                                <div class="absolute top-0 right-0 opacity-0 group-hover:opacity-100 transition-opacity flex gap-1 p-1 bg-white rounded shadow">
                                    <button onclick="editComponent({{ $component->id }})" class="p-1 text-gray-500 hover:text-blue-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button onclick="deleteComponent({{ $component->id }})" class="p-1 text-gray-500 hover:text-red-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Properties Panel -->
        <div class="w-80 bg-white border-l hidden" id="properties-panel">
            <div class="p-4 border-b">
                <div class="flex justify-between items-center">
                    <h2 class="font-semibold text-gray-900">Properties</h2>
                    <button onclick="closePropertiesPanel()" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="p-4 overflow-y-auto" id="properties-content">
                <!-- Properties will be loaded here -->
            </div>
        </div>
    </div>
    
    <!-- Overlay for Mobile Panels -->
    <div id="panel-overlay" class="panel-overlay" onclick="closeMobilePanels()"></div>
</div>

<!-- Preview Modal -->
<div id="preview-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-auto relative">
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900">Page Preview</h3>
            <button onclick="closePreview()" class="text-gray-400 hover:text-gray-500">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div id="preview-content" class="p-6">
            <!-- Preview content will be inserted here -->
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast" class="fixed top-4 right-4 z-50 transform transition-transform duration-300 ease-in-out translate-x-full">
    <div class="flex items-center p-4 text-sm rounded-lg shadow-lg min-w-[300px] max-w-md">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 rounded-lg me-3">
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            </svg>
        </div>
        <div class="ms-3 text-sm font-normal" id="toast-message"></div>
        <button type="button" class="ms-auto -mx-1.5 -my-1.5 rounded-lg p-1.5 inline-flex items-center justify-center h-8 w-8 hover:opacity-75" onclick="hideToast()">
            <span class="sr-only">Close</span>
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
        </button>
    </div>
</div>

<!-- Include SortableJS from CDN -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // Verify that required libraries are loaded
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Checking required libraries...');
        if (typeof Sortable === 'undefined') {
            console.error('SortableJS not loaded!');
        } else {
            console.log('SortableJS loaded successfully');
        }
        
        if (typeof jQuery === 'undefined') {
            console.error('jQuery not loaded!');
        } else {
            console.log('jQuery loaded successfully');
        }
    });
    const domainId = {{ $domain->id }};
    const domainUser = {
        name: '{{ $domain->user->name }}',
        profilePhoto: '{{ $domain->user->profile_photo ? asset("storage/" . $domain->user->profile_photo) : "" }}',
        slug: '{{ $domain->slug }}'
    };
    let currentComponentId = null;
    let currentComponentType = null;
    
    // Handle digital product file selection
    function handleDigitalProductSelect(input) {
        const file = input.files[0];
        const filename = document.getElementById('digital-product-filename');
        const uploadBtn = document.getElementById('digital-product-upload-button');
        const productInfo = document.getElementById('digital-product-info');
        
        if (file) {
            // Show filename and size
            filename.textContent = `Selected: ${file.name} (${formatFileSize(file.size)})`;
            // Hide previous upload info
            productInfo.classList.add('hidden');
            // Show upload button
            uploadBtn.classList.remove('hidden');
        } else {
            // Reset UI if no file selected
            filename.textContent = 'No file chosen';
            uploadBtn.classList.add('hidden');
        }
    }
    
    // Format number as Rupiah currency
    function formatRupiahValue(angka) {
        if (!angka) return '0';
        // If already formatted, return as is
        if (typeof angka === 'string' && angka.includes('.')) {
            return angka;
        }
        return parseInt(angka).toLocaleString('id-ID');
    }
    
    // Format input field as Rupiah currency
    function formatRupiah(input) {
        // Remove all non-digit characters
        let value = input.value.replace(/[\D]/g, '');
        
        // Format as Rupiah
        if (value) {
            input.value = parseInt(value).toLocaleString('id-ID');
        } else {
            input.value = '';
        }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM Content Loaded - Initializing builder...');
        
        // Make components draggable
        const componentItems = document.querySelectorAll('.component-item');
        console.log('Found component items:', componentItems.length);
        
        componentItems.forEach(item => {
            // Special handling for template component
            if (item.dataset.type === 'template') {
                // Tambahkan event listener untuk klik
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    openTemplateModal();
                });
                
                // Tambahkan event listener untuk drag
                item.addEventListener('dragstart', dragStart);
                item.addEventListener('dragend', dragEnd);
            } else {
                item.addEventListener('dragstart', dragStart);
                item.addEventListener('dragend', dragEnd);
            }
            console.log('Added event listeners to:', item.dataset.type);
        });
        
        // Make canvas a drop zone
        const canvas = document.getElementById('canvas');
        if (canvas) {
            canvas.addEventListener('dragover', dragOver);
            canvas.addEventListener('dragenter', dragEnter);
            canvas.addEventListener('dragleave', dragLeave);
            canvas.addEventListener('drop', drop);
        }
        
        // Inisialisasi event listener untuk template kosong yang sudah ada
        initializeEmptyTemplateListeners();
        
        // Initialize SortableJS for component reordering
        if (typeof Sortable !== 'undefined' && canvas) {
            console.log('Initializing Sortable...');
            const sortable = new Sortable(canvas, {
                animation: 150,
                ghostClass: 'sortable-ghost',
                dragClass: 'sortable-drag',
                chosenClass: 'sortable-chosen',
                handle: '.component-wrapper',
                group: {
                    name: 'shared',
                    pull: true,
                    put: true
                },
                onEnd: function (evt) {
                    // Update order on server
                    reorderComponents();
                }
            });
        } else {
            console.warn('SortableJS not loaded or canvas not found');
        }
        
        // Reattach event listeners when components are deleted
        observeCanvasChanges();
    });
    
    // Observe changes to the canvas to reattach event listeners when needed
    // Fungsi untuk menginisialisasi event listener untuk template kosong yang sudah ada
    function initializeEmptyTemplateListeners() {
        // Temukan semua template kosong
        const emptyTemplates = document.querySelectorAll('.component-wrapper[data-type="template"]');
        
        emptyTemplates.forEach(template => {
            try {
                const properties = JSON.parse(template.dataset.properties || '{}');
                if (properties.isEmpty === true) {
                    // Tambahkan event listener untuk klik
                    template.addEventListener('click', function() {
                        handleEmptyTemplateClick(this);
                    });
                }
            } catch (e) {
                console.error('Error parsing template properties:', e);
            }
        });
        
        // Also handle empty templates with the specific class
        const emptyTemplateComponents = document.querySelectorAll('.empty-template-component');
        emptyTemplateComponents.forEach(component => {
            component.addEventListener('click', function(e) {
                e.stopPropagation();
                handleEmptyTemplateClick(component);
            });
        });
    }
    
    function observeCanvasChanges() {
        const canvas = document.getElementById('canvas');
        if (!canvas) return;
        
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                // If nodes are added or removed
                if (mutation.type === 'childList') {
                    // Reattach drag and drop event listeners to canvas
                    canvas.removeEventListener('dragover', dragOver);
                    canvas.removeEventListener('dragenter', dragEnter);
                    canvas.removeEventListener('dragleave', dragLeave);
                    canvas.removeEventListener('drop', drop);
                    
                    canvas.addEventListener('dragover', dragOver);
                    canvas.addEventListener('dragenter', dragEnter);
                    canvas.addEventListener('dragleave', dragLeave);
                    canvas.addEventListener('drop', drop);
                    
                    // Inisialisasi ulang event listener untuk template kosong
                    initializeEmptyTemplateListeners();
                }
            });
        });
        
        observer.observe(canvas, { childList: true, subtree: true });
    }
    
    function dragStart(e) {
        console.log('Drag started:', e.target.dataset.type);
        
        // Validasi event
        if (!e || !e.target) {
            console.error('Invalid drag event');
            return;
        }
        
        // Pastikan target memiliki dataset.type
        if (!e.target.dataset || !e.target.dataset.type) {
            console.error('Component item missing type data');
            return;
        }
        
        try {
            e.dataTransfer.setData('text/plain', e.target.dataset.type);
            // Tambahkan flag untuk menandai ini adalah operasi drag
            e.dataTransfer.setData('isDragOperation', 'true');
            e.dataTransfer.effectAllowed = 'copy';
            
            // Add visual feedback
            e.target.classList.add('dragging');
            
            // Set drag image if needed
            const dragImage = e.target.cloneNode(true);
            dragImage.style.opacity = '0.5';
            document.body.appendChild(dragImage);
            e.dataTransfer.setDragImage(dragImage, 0, 0);
            setTimeout(() => document.body.removeChild(dragImage), 0);
        } catch (error) {
            console.error('Error in dragStart:', error);
        }
    }
    
    // Add dragEnd function to clean up visual feedback
    function dragEnd(e) {
        // Validasi event
        if (!e || !e.target) {
            console.error('Invalid drag event');
            return;
        }
        
        // Remove visual feedback
        e.target.classList.remove('dragging');
    }
    
    function dragOver(e) {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'copy';
        
        // Tambahkan visual feedback saat drag over canvas
        const canvas = document.getElementById('canvas');
        if (!canvas.classList.contains('drag-over')) {
            canvas.classList.add('drag-over');
        }
    }
    
    function dragEnter(e) {
        e.preventDefault();
        
        // Validasi event
        if (!e) {
            console.error('Invalid drag event');
            return;
        }
        
        const canvas = document.getElementById('canvas');
        if (canvas) {
            canvas.classList.add('drag-over');
        }
    }
    
    function dragLeave(e) {
        e.preventDefault();
        
        // Hanya hapus class jika mouse benar-benar keluar dari canvas
        const canvas = document.getElementById('canvas');
        const rect = canvas.getBoundingClientRect();
        const x = e.clientX;
        const y = e.clientY;
        
        // Jika mouse masih dalam bounds canvas, jangan hapus class
        if (x < rect.left || x >= rect.right || y < rect.top || y >= rect.bottom) {
            canvas.classList.remove('drag-over');
        }
    }
    
    function drop(e) {
        e.preventDefault();
        console.log('Drop event triggered');
        
        // Validasi event
        if (!e) {
            console.error('Invalid drop event');
            return;
        }
        
        const canvas = document.getElementById('canvas');
        if (canvas) {
            canvas.classList.remove('drag-over');
        }
        
        try {
            const type = e.dataTransfer.getData('text/plain');
            // Periksa apakah ini adalah operasi drag
            const isDragOperation = e.dataTransfer.getData('isDragOperation');
            console.log('Dropped component type:', type);
            
            if (type) {
                // Jika ini adalah template dan operasi drag, buat template kosong
                if (type === 'template' && isDragOperation === 'true') {
                    addComponentToCanvas(type, true); // true menandakan template kosong
                } else {
                    addComponentToCanvas(type);
                }
            }
        } catch (error) {
            console.error('Error in drop function:', error);
        }
    }
    
    function addComponentToCanvas(type, isEmptyTemplate = false) {
        // Remove empty canvas message if it exists
        const emptyMessage = document.getElementById('empty-canvas-message');
        if (emptyMessage) {
            emptyMessage.remove();
        }
        
        // Create a temporary ID for the new component
        const tempId = 'temp_' + Date.now();
        let componentHtml = '';
        
        switch(type) {
            case 'text':
                componentHtml = `
                    <div class="text-content">
                        <p>Edit this text content</p>
                    </div>
                `;
                break;
            case 'button':
                componentHtml = `
                    <a href="#" class="px-4 py-2 bg-primary hover:bg-primary-dark text-white rounded-lg block text-center">
                        Button Text
                    </a>
                `;
                break;
            case 'image':
                componentHtml = `
                    <div class="resizable-image-container" style="position: relative; display: inline-block; width: 100%; height: auto;">
                        <img src="https://placehold.co/400x200" alt="Image" class="resizable-image-element" style="width: 100%; height: 100%; display: block;">
                    </div>
                `;
                break;
            case 'link':
                componentHtml = `
                    <a href="#" class="text-primary underline">
                        Link text
                    </a>
                `;
                break;
            case 'divider':
                componentHtml = `<hr class="border-gray-300">`;
                break;
            case 'profile':
                componentHtml = `
                    <div class="flex items-center gap-4 p-4">
                        ${domainUser.profilePhoto ? 
                            `<img src="${domainUser.profilePhoto}" alt="${domainUser.name}" class="w-16 h-16 rounded-full object-cover">` :
                            `<div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center">
                                <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>`
                        }
                        <div>
                            <h3 class="font-semibold text-lg text-gray-900">${domainUser.name}</h3>
                            <p class="text-gray-600 text-sm">@${domainUser.slug}</p>
                        </div>
                    </div>
                `;
                break;
            case 'template':
                if (isEmptyTemplate) {
                    // Template kosong dengan tampilan khusus
                    componentHtml = `
                        <div class="max-w-sm rounded overflow-hidden shadow-lg bg-white border-2 border-dashed border-gray-300 cursor-pointer empty-template-component" onclick="handleEmptyTemplateClick(this)">
                            <div class="bg-gray-100 h-48 flex items-center justify-center">
                                <div class="text-center p-4">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Empty Template</h3>
                                    <p class="mt-1 text-sm text-gray-500">Click to add product</p>
                                </div>
                            </div>
                            <div class="px-6 py-4">
                                <div class="font-bold text-xl mb-2 text-gray-400">No Product</div>
                                <p class="text-gray-500 text-base">
                                    <a href="/cms/products" class="text-primary hover:underline">Add products in the product page</a>
                                </p>
                            </div>
                            <div class="px-6 pt-4 pb-2 flex justify-between items-center">
                                <span class="text-xl font-bold text-primary">Rp 0</span>
                                <button class="bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded">
                                    Add Product
                                </button>
                            </div>
                        </div>
                    `;
                } else {
                    // Template biasa
                    componentHtml = `
                        <div class="max-w-sm rounded overflow-hidden shadow-lg bg-white">
                            <img class="w-full h-48 object-cover" src="https://placehold.co/400x300" alt="Template Image">
                            <div class="px-6 py-4">
                                <div class="font-bold text-xl mb-2">Template Title</div>
                                <p class="text-gray-700 text-base">
                                    Template description goes here.
                                </p>
                            </div>
                            <div class="px-6 pt-4 pb-2 flex justify-between items-center">
                                <span class="text-xl font-bold text-primary">Rp 0</span>
                                <button class="bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded">
                                    Buy Now
                                </button>
                            </div>
                        </div>
                    `;
                }
                break;
            default:
                componentHtml = `<div class="text-red-500 p-2">Unknown component type: ${type}</div>`;
                console.warn('Unknown component type:', type);
        }
        
        const componentWrapper = document.createElement('div');
        componentWrapper.className = 'component-wrapper relative group border border-transparent hover:border-dashed hover:border-gray-300 rounded p-2';
        componentWrapper.dataset.id = tempId;
        componentWrapper.dataset.type = type;
        componentWrapper.dataset.properties = JSON.stringify(getDefaultProperties(type));
        componentWrapper.innerHTML = `
            <div class="component-content">
                ${componentHtml}
            </div>
            <div class="absolute top-0 right-0 opacity-0 group-hover:opacity-100 transition-opacity flex gap-1 p-1 bg-white rounded shadow">
                <button onclick="editComponent('${tempId}')" class="p-1 text-gray-500 hover:text-blue-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </button>
                <button onclick="deleteComponent('${tempId}')" class="p-1 text-gray-500 hover:text-red-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
        `;
        
        document.getElementById('canvas').appendChild(componentWrapper);
        
        // Add resize handles for image components
        if (type === 'image') {
            addResizeHandles(componentWrapper);
        }
        
        // Jika ini adalah template kosong, simpan ke database dengan flag isEmpty
        if (type === 'template' && isEmptyTemplate) {
            // Tambahkan flag isEmpty ke properties
            const properties = JSON.parse(componentWrapper.dataset.properties || '{}');
            properties.isEmpty = true;
            componentWrapper.dataset.properties = JSON.stringify(properties);
            
            // Simpan ke server dengan flag isEmpty
            saveEmptyTemplateComponent(type, componentWrapper);
            
            // Tambahkan event listener untuk template kosong
            componentWrapper.addEventListener('click', function() {
                handleEmptyTemplateClick(this);
            });
            
            return;
        }
        
        // Save component to server
        saveComponent(type, componentWrapper);
    }
    
    function saveComponent(type, componentElement) {
        const order = Array.from(document.getElementById('canvas').children).indexOf(componentElement);
        const properties = getDefaultProperties(type);
        
        // Validasi order
        if (order === -1) {
            console.error('Component not found in canvas');
            showToast('Error saving component. Please try again.', 'error');
            componentElement.remove();
            return;
        }
        
        // If this is an empty template, don't save it to the server
        if (type === 'template' && properties.isEmpty === true) {
            console.log('Skipping save for empty template');
            return;
        }
        
        fetch(`/cms/builder/${domainId}/component`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                type: type,
                properties: properties,
                order: order
            })
        })
        .then(response => {
            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('Received non-JSON response: ' + response.status + ' ' + response.statusText);
            }
            
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status + ' ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            // Validasi data yang diterima
            if (!data || !data.id) {
                throw new Error('Invalid response data');
            }
            
            // Update the component with the real ID from the server
            const tempId = componentElement.dataset.id;
            componentElement.dataset.id = data.id;
            
            // Update the onclick handlers with the real ID
            const editButton = componentElement.querySelector('button[onclick*="editComponent"]');
            const deleteButton = componentElement.querySelector('button[onclick*="deleteComponent"]');
            
            if (editButton) {
                editButton.setAttribute('onclick', `editComponent(${data.id})`);
            }
            
            if (deleteButton) {
                deleteButton.setAttribute('onclick', `deleteComponent(${data.id})`);
            }
            
            // Show success message
            showToast('Component added successfully!', 'success');
        })
        .catch(error => {
            console.error('Error saving component:', error);
            showToast('Error saving component. Please try again.', 'error');
            // Remove the component from DOM if save failed
            try {
                if (componentElement.parentNode) {
                    componentElement.remove();
                }
            } catch (e) {
                console.error('Error removing component element:', e);
            }
        });
    }
    
    function saveEmptyTemplateComponent(type, componentElement) {
        const order = Array.from(document.getElementById('canvas').children).indexOf(componentElement);
        const properties = JSON.parse(componentElement.dataset.properties || '{}');
        
        // Validasi order
        if (order === -1) {
            console.error('Component not found in canvas');
            showToast('Error saving component. Please try again.', 'error');
            componentElement.remove();
            return;
        }
        
        fetch(`/cms/builder/${domainId}/component`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                type: type,
                properties: properties,
                order: order
            })
        })
        .then(response => {
            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('Received non-JSON response: ' + response.status + ' ' + response.statusText);
            }
            
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status + ' ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            // Validasi data yang diterima
            if (!data || !data.id) {
                throw new Error('Invalid response data');
            }
            
            // Update the component with the real ID from the server
            const tempId = componentElement.dataset.id;
            componentElement.dataset.id = data.id;
            
            // Update the onclick handlers with the real ID
            const editButton = componentElement.querySelector('button[onclick*="editComponent"]');
            const deleteButton = componentElement.querySelector('button[onclick*="deleteComponent"]');
            
            if (editButton) {
                editButton.setAttribute('onclick', `editComponent(${data.id})`);
            }
            
            if (deleteButton) {
                deleteButton.setAttribute('onclick', `deleteComponent(${data.id})`);
            }
            
            // Show success message
            showToast('Empty template added successfully!', 'success');
        })
        .catch(error => {
            console.error('Error saving empty template:', error);
            showToast('Error saving empty template. Please try again.', 'error');
            // Remove the component from DOM if save failed
            try {
                if (componentElement.parentNode) {
                    componentElement.remove();
                }
            } catch (e) {
                console.error('Error removing component element:', e);
            }
        });
    }
    
    // Fungsi untuk menangani klik pada template kosong
    function handleEmptyTemplateClick(element) {
        // Temukan wrapper component
        const componentWrapper = element.closest ? element.closest('.component-wrapper') : element;
        if (!componentWrapper) return;
        
        // Get component ID
        const componentId = componentWrapper.dataset.id;
        if (!componentId) return;
        
        // Set current component and open properties panel
        currentComponentId = componentId;
        currentComponentType = 'template';
        
        // Remove selected class from all components
        document.querySelectorAll('.component-wrapper').forEach(comp => {
            comp.classList.remove('selected');
        });
        
        // Add selected class to current component
        componentWrapper.classList.add('selected');
        
        // Show properties panel
        const propertiesPanel = document.getElementById('properties-panel');
        if (propertiesPanel) {
            propertiesPanel.classList.remove('hidden');
        }
        
        // Load properties for empty template
        loadProperties(componentWrapper);
        
        showToast('Fill in the product details in the properties panel to create a new product.', 'info');
    }
    
    // Template kosong tidak lagi memerlukan fungsi khusus karena tidak disimpan ke database
    
    // Fungsi updateComponentOnServer dihapus karena template kosong tidak disimpan ke database
    
    function getDefaultProperties(type) {
        // Validasi parameter
        if (!type) {
            console.error('Invalid component type');
            return {};
        }
        
        switch(type) {
            case 'text':
                return { 
                    content: '<p>Edit this text content</p>',
                    alignment: 'left',
                    size: 'text-base',
                    bold: false,
                    italic: false,
                    underline: false
                };
            case 'button':
                return { 
                    text: 'Button Text', 
                    url: '#',
                    backgroundColor: '#00c499',
                    textColor: '#ffffff',
                    borderColor: '#00c499',
                    borderWidth: '0',
                    borderRadius: '0.5rem',
                    padding: 'px-4 py-2',
                    fontSize: 'text-base',
                    fontWeight: 'font-normal'
                };
            case 'image':
                return { 
                    src: 'https://placehold.co/400x200', 
                    alt: 'Image',
                    width: '100%',
                    height: 'auto'
                };
            case 'link':
                return { 
                    text: 'Link text', 
                    url: '#',
                    target: '_blank',
                    textColor: '#00c499',
                    fontSize: 'text-base',
                    fontWeight: 'font-normal',
                    textDecoration: 'underline'
                };
            case 'divider':
                return {
                    style: 'solid',
                    color: '#e5e7eb',
                    thickness: '1px'
                };
            case 'profile':
                return {
                    showPhoto: true,
                    showName: true,
                    showUsername: true,
                    alignment: 'left',
                    layout: 'horizontal',
                    photoSize: 'medium'
                };
            case 'template':
                return {
                    image: 'https://placehold.co/400x300',
                    title: 'Template Title',
                    description: 'Template description goes here.',
                    price: 0,
                    buttonText: 'Buy Now',
                    digitalProduct: {
                        path: '',
                        originalName: '',
                        fileType: '',
                        fileSize: 0
                    },
                    isEmpty: false // Default tidak kosong
                };
            default:
                console.warn('Unknown component type:', type);
                return {};
        }
    }
    
    function editComponent(componentId) {
        console.log('Editing component:', componentId);
        
        // Validasi parameter
        if (!componentId) {
            console.error('Invalid component ID');
            showToast('Invalid component ID. Please try again.', 'error');
            return;
        }
        
        try {
            const component = document.querySelector(`.component-wrapper[data-id="${componentId}"]`);
            if (!component) {
                console.error('Component not found:', componentId);
                showToast('Component not found. Please try again.', 'error');
                return;
            }
            
            // Remove selected class from all components
            document.querySelectorAll('.component-wrapper').forEach(comp => {
                comp.classList.remove('selected');
            });
            
            // Add selected class to current component
            component.classList.add('selected');
            
            currentComponentId = componentId;
            currentComponentType = component.dataset.type;
            
            console.log('Component type:', currentComponentType);
            
            // Show properties panel
            const propertiesPanel = document.getElementById('properties-panel');
            if (propertiesPanel) {
                propertiesPanel.classList.remove('hidden');
                console.log('Properties panel shown');
            }
            
            // Load properties based on component type
            loadProperties(component);
        } catch (error) {
            console.error('Error in editComponent:', error);
            showToast('Error editing component. Please try again.', 'error');
        }
    }
    
    function loadProperties(component) {
        const propertiesContent = document.getElementById('properties-content');
        if (!propertiesContent) {
            console.error('Properties content element not found');
            showToast('Error loading properties. Please try again.', 'error');
            return;
        }
        
        let properties = {};
        
        try {
            properties = JSON.parse(component.dataset.properties || '{}');
        } catch (e) {
            console.error('Error parsing component properties:', e);
            properties = {};
        }
        
        // If component has digital_product_path in dataset, sync it to properties
        if (component.dataset.digitalProductPath && !properties.digitalProduct) {
            properties.digitalProduct = {
                path: component.dataset.digitalProductPath,
                originalName: 'Uploaded Product',
                fileType: 'unknown',
                fileSize: 0
            };
        }
        
        let html = '';
        
        switch(component.dataset.type) {
            case 'text':
                html = `
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                            <textarea id="text-content" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary" rows="4">${properties.content || ''}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Text Alignment</label>
                            <select id="text-alignment" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="left" ${properties.alignment === 'left' ? 'selected' : ''}>Left</option>
                                <option value="center" ${properties.alignment === 'center' ? 'selected' : ''}>Center</option>
                                <option value="right" ${properties.alignment === 'right' ? 'selected' : ''}>Right</option>
                                <option value="justify" ${properties.alignment === 'justify' ? 'selected' : ''}>Justify</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Text Size</label>
                            <select id="text-size" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="text-xs" ${properties.size === 'text-xs' ? 'selected' : ''}>Extra Small</option>
                                <option value="text-sm" ${properties.size === 'text-sm' ? 'selected' : ''}>Small</option>
                                <option value="text-base" ${properties.size === 'text-base' ? 'selected' : ''}>Base</option>
                                <option value="text-lg" ${properties.size === 'text-lg' ? 'selected' : ''}>Large</option>
                                <option value="text-xl" ${properties.size === 'text-xl' ? 'selected' : ''}>Extra Large</option>
                                <option value="text-2xl" ${properties.size === 'text-2xl' ? 'selected' : ''}>2X Large</option>
                                <option value="text-3xl" ${properties.size === 'text-3xl' ? 'selected' : ''}>3X Large</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Text Formatting</label>
                            <div class="flex gap-2">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" id="text-bold" class="rounded border-gray-300 text-primary focus:ring-primary" ${properties.bold ? 'checked' : ''}>
                                    <span class="ml-2">Bold</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" id="text-italic" class="rounded border-gray-300 text-primary focus:ring-primary" ${properties.italic ? 'checked' : ''}>
                                    <span class="ml-2">Italic</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" id="text-underline" class="rounded border-gray-300 text-primary focus:ring-primary" ${properties.underline ? 'checked' : ''}>
                                    <span class="ml-2">Underline</span>
                                </label>
                            </div>
                        </div>
                        <button onclick="saveComponentProperties()" class="w-full bg-primary hover:bg-primary-dark text-white py-2 rounded-lg">
                            Save Changes
                        </button>
                    </div>
                `;
                break;
            case 'button':
                html = `
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Button Text</label>
                            <input type="text" id="button-text" value="${properties.text || ''}" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">URL</label>
                            <input type="text" id="button-url" value="${properties.url || ''}" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Background Color</label>
                            <div class="flex items-center gap-2">
                                <input type="color" id="button-bg-color" value="${properties.backgroundColor || '#00c499'}" class="w-10 h-10 border border-gray-300 rounded cursor-pointer">
                                <input type="text" id="button-bg-color-value" value="${properties.backgroundColor || '#00c499'}" class="flex-1 rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Text Color</label>
                            <div class="flex items-center gap-2">
                                <input type="color" id="button-text-color" value="${properties.textColor || '#ffffff'}" class="w-10 h-10 border border-gray-300 rounded cursor-pointer">
                                <input type="text" id="button-text-color-value" value="${properties.textColor || '#ffffff'}" class="flex-1 rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Border Color</label>
                            <div class="flex items-center gap-2">
                                <input type="color" id="button-border-color" value="${properties.borderColor || '#00c499'}" class="w-10 h-10 border border-gray-300 rounded cursor-pointer">
                                <input type="text" id="button-border-color-value" value="${properties.borderColor || '#00c499'}" class="flex-1 rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Border Width</label>
                            <select id="button-border-width" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="0" ${properties.borderWidth === '0' ? 'selected' : ''}>None</option>
                                <option value="1" ${properties.borderWidth === '1' ? 'selected' : ''}>1px</option>
                                <option value="2" ${properties.borderWidth === '2' ? 'selected' : ''}>2px</option>
                                <option value="4" ${properties.borderWidth === '4' ? 'selected' : ''}>4px</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Border Radius</label>
                            <select id="button-border-radius" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="none" ${properties.borderRadius === 'none' ? 'selected' : ''}>None</option>
                                <option value="rounded" ${properties.borderRadius === 'rounded' ? 'selected' : ''}>Small</option>
                                <option value="rounded-md" ${properties.borderRadius === 'rounded-md' ? 'selected' : ''}>Medium</option>
                                <option value="rounded-lg" ${properties.borderRadius === 'rounded-lg' ? 'selected' : ''}>Large</option>
                                <option value="rounded-full" ${properties.borderRadius === 'rounded-full' ? 'selected' : ''}>Full</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Padding</label>
                            <select id="button-padding" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="px-2 py-1" ${properties.padding === 'px-2 py-1' ? 'selected' : ''}>Small</option>
                                <option value="px-4 py-2" ${properties.padding === 'px-4 py-2' ? 'selected' : ''}>Medium</option>
                                <option value="px-6 py-3" ${properties.padding === 'px-6 py-3' ? 'selected' : ''}>Large</option>
                                <option value="px-8 py-4" ${properties.padding === 'px-8 py-4' ? 'selected' : ''}>Extra Large</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Font Size</label>
                            <select id="button-font-size" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="text-xs" ${properties.fontSize === 'text-xs' ? 'selected' : ''}>Extra Small</option>
                                <option value="text-sm" ${properties.fontSize === 'text-sm' ? 'selected' : ''}>Small</option>
                                <option value="text-base" ${properties.fontSize === 'text-base' ? 'selected' : ''}>Base</option>
                                <option value="text-lg" ${properties.fontSize === 'text-lg' ? 'selected' : ''}>Large</option>
                                <option value="text-xl" ${properties.fontSize === 'text-xl' ? 'selected' : ''}>Extra Large</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Font Weight</label>
                            <select id="button-font-weight" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="font-thin" ${properties.fontWeight === 'font-thin' ? 'selected' : ''}>Thin</option>
                                <option value="font-light" ${properties.fontWeight === 'font-light' ? 'selected' : ''}>Light</option>
                                <option value="font-normal" ${properties.fontWeight === 'font-normal' ? 'selected' : ''}>Normal</option>
                                <option value="font-medium" ${properties.fontWeight === 'font-medium' ? 'selected' : ''}>Medium</option>
                                <option value="font-semibold" ${properties.fontWeight === 'font-semibold' ? 'selected' : ''}>Semi Bold</option>
                                <option value="font-bold" ${properties.fontWeight === 'font-bold' ? 'selected' : ''}>Bold</option>
                            </select>
                        </div>
                        <button onclick="saveComponentProperties()" class="w-full bg-primary hover:bg-primary-dark text-white py-2 rounded-lg">
                            Save Changes
                        </button>
                    </div>
                `;
                break;
            case 'image':
                html = `
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Image Source</label>
                            <div class="flex gap-2 mb-2">
                                <button type="button" class="tab-button active px-3 py-1 text-sm rounded-md bg-gray-100" onclick="switchImageTab('url')">URL</button>
                                <button type="button" class="tab-button px-3 py-1 text-sm rounded-md" onclick="switchImageTab('upload')">Upload</button>
                            </div>
                            
                            <!-- URL Tab -->
                            <div id="url-tab" class="tab-content">
                                <input type="text" id="image-src" value="${properties.src || ''}" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary" placeholder="https://example.com/image.jpg">
                            </div>
                            
                            <!-- Upload Tab -->
                            <div id="upload-tab" class="tab-content hidden">
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center">
                                    <input type="file" id="image-upload" accept="image/*" class="hidden">
                                    <button type="button" onclick="document.getElementById('image-upload').click()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-md text-gray-700">
                                        Choose Image
                                    </button>
                                    <p id="upload-filename" class="mt-2 text-sm text-gray-500">No file chosen</p>
                                    <button type="button" id="upload-button" onclick="uploadImageFile()" class="mt-2 px-4 py-2 bg-primary hover:bg-primary-dark text-white rounded-md hidden">
                                        Upload Image
                                    </button>
                                    <p class="mt-2 text-xs text-gray-500">Upload file gambar dibawah 2MB</p>
                                </div>
                                <div id="upload-progress" class="mt-2 hidden">
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div id="upload-progress-bar" class="bg-primary h-2 rounded-full" style="width: 0%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alt Text</label>
                            <input type="text" id="image-alt" value="${properties.alt || ''}" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Width</label>
                            <input type="text" id="image-width" value="${properties.width || '100%'}" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Height</label>
                            <input type="text" id="image-height" value="${properties.height || 'auto'}" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <button onclick="saveComponentProperties()" class="w-full bg-primary hover:bg-primary-dark text-white py-2 rounded-lg">
                            Save Changes
                        </button>
                    </div>
                `;
                break;
            case 'link':
                html = `
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Link Text</label>
                            <input type="text" id="link-text" value="${properties.text || ''}" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">URL</label>
                            <input type="text" id="link-url" value="${properties.url || ''}" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Target</label>
                            <select id="link-target" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="_self" ${properties.target === '_self' ? 'selected' : ''}>Same Window</option>
                                <option value="_blank" ${properties.target === '_blank' ? 'selected' : ''}>New Window</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Text Color</label>
                            <div class="flex items-center gap-2">
                                <input type="color" id="link-text-color" value="${properties.textColor || '#00c499'}" class="w-10 h-10 border border-gray-300 rounded cursor-pointer">
                                <input type="text" id="link-text-color-value" value="${properties.textColor || '#00c499'}" class="flex-1 rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Font Size</label>
                            <select id="link-font-size" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="text-xs" ${properties.fontSize === 'text-xs' ? 'selected' : ''}>Extra Small</option>
                                <option value="text-sm" ${properties.fontSize === 'text-sm' ? 'selected' : ''}>Small</option>
                                <option value="text-base" ${properties.fontSize === 'text-base' ? 'selected' : ''}>Base</option>
                                <option value="text-lg" ${properties.fontSize === 'text-lg' ? 'selected' : ''}>Large</option>
                                <option value="text-xl" ${properties.fontSize === 'text-xl' ? 'selected' : ''}>Extra Large</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Font Weight</label>
                            <select id="link-font-weight" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="font-thin" ${properties.fontWeight === 'font-thin' ? 'selected' : ''}>Thin</option>
                                <option value="font-light" ${properties.fontWeight === 'font-light' ? 'selected' : ''}>Light</option>
                                <option value="font-normal" ${properties.fontWeight === 'font-normal' ? 'selected' : ''}>Normal</option>
                                <option value="font-medium" ${properties.fontWeight === 'font-medium' ? 'selected' : ''}>Medium</option>
                                <option value="font-semibold" ${properties.fontWeight === 'font-semibold' ? 'selected' : ''}>Semi Bold</option>
                                <option value="font-bold" ${properties.fontWeight === 'font-bold' ? 'selected' : ''}>Bold</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Text Decoration</label>
                            <select id="link-text-decoration" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="no-underline" ${properties.textDecoration === 'no-underline' ? 'selected' : ''}>None</option>
                                <option value="underline" ${properties.textDecoration === 'underline' ? 'selected' : ''}>Underline</option>
                                <option value="overline" ${properties.textDecoration === 'overline' ? 'selected' : ''}>Overline</option>
                                <option value="line-through" ${properties.textDecoration === 'line-through' ? 'selected' : ''}>Line Through</option>
                            </select>
                        </div>
                        <button onclick="saveComponentProperties()" class="w-full bg-primary hover:bg-primary-dark text-white py-2 rounded-lg">
                            Save Changes
                        </button>
                    </div>
                `;
                break;
            case 'divider':
                html = `
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Style</label>
                            <select id="divider-style" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="solid" ${properties.style === 'solid' ? 'selected' : ''}>Solid</option>
                                <option value="dashed" ${properties.style === 'dashed' ? 'selected' : ''}>Dashed</option>
                                <option value="dotted" ${properties.style === 'dotted' ? 'selected' : ''}>Dotted</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                            <input type="color" id="divider-color" value="${properties.color || '#e5e7eb'}" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Thickness</label>
                            <input type="text" id="divider-thickness" value="${properties.thickness || '1px'}" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <button onclick="saveComponentProperties()" class="w-full bg-primary hover:bg-primary-dark text-white py-2 rounded-lg">
                            Save Changes
                        </button>
                    </div>
                `;
                break;
            case 'profile':
                html = `
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Layout Style</label>
                            <select id="profile-layout" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="horizontal" ${properties.layout === 'horizontal' || !properties.layout ? 'selected' : ''}>Horizontal (Photo + Text Side by Side)</option>
                                <option value="vertical" ${properties.layout === 'vertical' ? 'selected' : ''}>Vertical (Photo on Top)</option>
                                <option value="compact" ${properties.layout === 'compact' ? 'selected' : ''}>Compact (Small Photo + Text)</option>
                                <option value="card" ${properties.layout === 'card' ? 'selected' : ''}>Card (With Background)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Display Options</label>
                            <div class="space-y-2">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" id="profile-show-photo" class="rounded border-gray-300 text-primary focus:ring-primary" ${properties.showPhoto !== false ? 'checked' : ''}>
                                    <span class="ml-2">Show Photo</span>
                                </label>
                            </div>
                            <div class="space-y-2 mt-2">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" id="profile-show-name" class="rounded border-gray-300 text-primary focus:ring-primary" ${properties.showName !== false ? 'checked' : ''}>
                                    <span class="ml-2">Show Name</span>
                                </label>
                            </div>
                            <div class="space-y-2 mt-2">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" id="profile-show-username" class="rounded border-gray-300 text-primary focus:ring-primary" ${properties.showUsername !== false ? 'checked' : ''}>
                                    <span class="ml-2">Show Username</span>
                                </label>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alignment</label>
                            <select id="profile-alignment" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="left" ${properties.alignment === 'left' ? 'selected' : ''}>Left</option>
                                <option value="center" ${properties.alignment === 'center' ? 'selected' : ''}>Center</option>
                                <option value="right" ${properties.alignment === 'right' ? 'selected' : ''}>Right</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Photo Size</label>
                            <select id="profile-photo-size" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="small" ${properties.photoSize === 'small' ? 'selected' : ''}>Small (48px)</option>
                                <option value="medium" ${properties.photoSize === 'medium' || !properties.photoSize ? 'selected' : ''}>Medium (64px)</option>
                                <option value="large" ${properties.photoSize === 'large' ? 'selected' : ''}>Large (96px)</option>
                                <option value="xlarge" ${properties.photoSize === 'xlarge' ? 'selected' : ''}>Extra Large (128px)</option>
                            </select>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-lg">
                            <p class="text-sm text-blue-800">
                                <strong>Note:</strong> Profile akan menampilkan foto dan nama dari user pemilik domain.
                            </p>
                        </div>
                        <button onclick="saveComponentProperties()" class="w-full bg-primary hover:bg-primary-dark text-white py-2 rounded-lg">
                            Save Changes
                        </button>
                    </div>
                `;
                break;
            case 'template':
                // Check if this is an empty template
                const isEmptyTemplate = properties.isEmpty === true;
                
                if (isEmptyTemplate) {
                    html = `
                        <div class="space-y-4">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-blue-800">Empty Template</span>
                                </div>
                                <p class="text-sm text-blue-700 mt-1">Fill in the product details below to create a new product automatically.</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Product Image</label>
                                <div class="flex gap-2 mb-2">
                                    <button type="button" class="tab-button active px-3 py-1 text-sm rounded-md bg-gray-100" onclick="switchTemplateImageTab('url')">URL</button>
                                    <button type="button" class="tab-button px-3 py-1 text-sm rounded-md" onclick="switchTemplateImageTab('upload')">Upload</button>
                                </div>
                                
                                <!-- URL Tab -->
                                <div id="template-url-tab" class="tab-content">
                                    <input type="text" id="template-image" value="${properties.image || 'https://placehold.co/400x300'}" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary" placeholder="https://example.com/image.jpg">
                                </div>
                                
                                <!-- Upload Tab -->
                                <div id="template-upload-tab" class="tab-content hidden">
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center">
                                        <input type="file" id="template-image-upload" accept="image/*" class="hidden">
                                        <button type="button" onclick="document.getElementById('template-image-upload').click()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-md text-gray-700">
                                            Choose Image
                                        </button>
                                        <p id="template-upload-filename" class="mt-2 text-sm text-gray-500">No file chosen</p>
                                        <button type="button" id="template-upload-button" onclick="uploadTemplateImageFile()" class="mt-2 px-4 py-2 bg-primary hover:bg-primary-dark text-white rounded-md hidden">
                                            Upload Image
                                        </button>
                                        <p class="mt-2 text-xs text-gray-500">Upload file gambar dibawah 2MB</p>
                                    </div>
                                    <div id="template-upload-progress" class="mt-2 hidden">
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div id="template-upload-progress-bar" class="bg-primary h-2 rounded-full" style="width: 0%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Product Name *</label>
                                <input type="text" id="template-title" value="${properties.title || ''}" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Enter product name" required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                                <textarea id="template-description" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary" rows="3" placeholder="Enter product description" required>${properties.description || ''}</textarea>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Price (Rp) *</label>
                                <input type="text" id="template-price" value="${formatRupiahValue(properties.price) || '0'}" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary" oninput="formatRupiah(this)" placeholder="0" required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Button Text</label>
                                <input type="text" id="template-button-text" value="${properties.buttonText || 'Buy Now'}" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Buy Now">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Digital Product File</label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4">
                                    <input type="file" id="digital-product-upload" onchange="handleDigitalProductSelect(this)" class="hidden" accept=".pdf,.zip,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.gif">
                                    <div class="text-center">
                                        <button type="button" onclick="document.getElementById('digital-product-upload').click()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-md text-gray-700">
                                            Choose Digital Product
                                        </button>
                                        <p id="digital-product-filename" class="mt-2 text-sm text-gray-500">
                                            ${properties.digitalProduct && properties.digitalProduct.originalName ? properties.digitalProduct.originalName : 'No file chosen'}
                                        </p>
                                        <button type="button" id="digital-product-upload-button" onclick="uploadDigitalProduct()" class="mt-2 px-4 py-2 bg-primary hover:bg-primary-dark text-white rounded-md hidden">
                                            Upload Product
                                        </button>
                                        <p class="mt-2 text-xs text-gray-500">Upload PDF, ZIP, DOC, XLS, or image files (max 10MB)</p>
                                    </div>
                                    <div id="digital-product-upload-progress" class="mt-2 hidden">
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div id="digital-product-upload-progress-bar" class="bg-primary h-2 rounded-full" style="width: 0%"></div>
                                        </div>
                                    </div>
                                    <div id="digital-product-info" class="mt-2 text-sm text-gray-600 ${properties.digitalProduct && properties.digitalProduct.path ? '' : 'hidden'}">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span>Product file uploaded</span>
                                        </div>
                                        ${properties.digitalProduct && properties.digitalProduct.fileSize ? 
                                            `<p class="mt-1 text-xs">File size: ${formatFileSize(properties.digitalProduct.fileSize)}</p>` : ''}
                                    </div>
                                </div>
                            </div>
                            
                            <button onclick="saveEmptyTemplateAsProduct()" class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg mt-4">
                                Create Product & Update Template
                            </button>
                        </div>
                    `;
                } else {
                    // Regular template properties (existing code)
                    html = `
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Image</label>
                                <div class="flex gap-2 mb-2">
                                    <button type="button" class="tab-button active px-3 py-1 text-sm rounded-md bg-gray-100" onclick="switchTemplateImageTab('url')">URL</button>
                                    <button type="button" class="tab-button px-3 py-1 text-sm rounded-md" onclick="switchTemplateImageTab('upload')">Upload</button>
                                </div>
                                
                                <!-- URL Tab -->
                                <div id="template-url-tab" class="tab-content">
                                    <input type="text" id="template-image" value="${properties.image || 'https://placehold.co/400x300'}" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary" placeholder="https://example.com/image.jpg">
                                </div>
                                
                                <!-- Upload Tab -->
                                <div id="template-upload-tab" class="tab-content hidden">
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center">
                                        <input type="file" id="template-image-upload" accept="image/*" class="hidden">
                                        <button type="button" onclick="document.getElementById('template-image-upload').click()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-md text-gray-700">
                                            Choose Image
                                        </button>
                                        <p id="template-upload-filename" class="mt-2 text-sm text-gray-500">No file chosen</p>
                                        <button type="button" id="template-upload-button" onclick="uploadTemplateImageFile()" class="mt-2 px-4 py-2 bg-primary hover:bg-primary-dark text-white rounded-md hidden">
                                            Upload Image
                                        </button>
                                        <p class="mt-2 text-xs text-gray-500">Upload file gambar dibawah 2MB</p>
                                    </div>
                                    <div id="template-upload-progress" class="mt-2 hidden">
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div id="template-upload-progress-bar" class="bg-primary h-2 rounded-full" style="width: 0%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                                <input type="text" id="template-title" value="${properties.title || 'Template Title'}" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <textarea id="template-description" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary" rows="3">${properties.description || 'Template description goes here.'}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Price (Rp)</label>
                                <input type="text" id="template-price" value="${formatRupiahValue(properties.price) || '0'}" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary" oninput="formatRupiah(this)">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Button Text</label>
                                <input type="text" id="template-button-text" value="${properties.buttonText || 'Buy Now'}" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Digital Product</label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4">
                                    <input type="file" id="digital-product-upload" onchange="handleDigitalProductSelect(this)" class="hidden" accept=".pdf,.zip,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.gif">
                                    <div class="text-center">
                                        <button type="button" onclick="document.getElementById('digital-product-upload').click()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-md text-gray-700">
                                            Choose Digital Product
                                        </button>
                                        <p id="digital-product-filename" class="mt-2 text-sm text-gray-500">
                                            ${properties.digitalProduct && properties.digitalProduct.originalName ? properties.digitalProduct.originalName : 'No file chosen'}
                                        </p>
                                        <button type="button" id="digital-product-upload-button" onclick="uploadDigitalProduct()" class="mt-2 px-4 py-2 bg-primary hover:bg-primary-dark text-white rounded-md hidden">
                                            Upload Product
                                        </button>
                                        <p class="mt-2 text-xs text-gray-500">Upload PDF, ZIP, DOC, XLS, or image files (max 10MB)</p>
                                    </div>
                                    <div id="digital-product-upload-progress" class="mt-2 hidden">
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div id="digital-product-upload-progress-bar" class="bg-primary h-2 rounded-full" style="width: 0%"></div>
                                        </div>
                                    </div>
                                    <div id="digital-product-info" class="mt-2 text-sm text-gray-600 ${properties.digitalProduct && properties.digitalProduct.path ? '' : 'hidden'}">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span>Product file uploaded</span>
                                        </div>
                                        ${properties.digitalProduct && properties.digitalProduct.fileSize ? 
                                            `<p class="mt-1 text-xs">File size: ${formatFileSize(properties.digitalProduct.fileSize)}</p>` : ''}
                                    </div>
                                </div>
                            </div>
                            <button onclick="saveComponentProperties()" class="w-full bg-primary hover:bg-primary-dark text-white py-2 rounded-lg mt-4">
                                Save Changes
                            </button>
                        </div>
                    `;
                }
                break;
            default:
                html = '<p class="text-red-500">No properties to edit for this component type.</p>';
        }
        
        propertiesContent.innerHTML = html;
        
        // Add real-time preview functionality
        addRealTimePreviewListeners(component);
    }
    
    function addRealTimePreviewListeners(component) {
        // Add event listeners for real-time preview updates
        const inputs = document.querySelectorAll('#properties-content input, #properties-content select, #properties-content textarea');
        inputs.forEach(input => {
            // For color inputs, we need to listen to both input and change events
            if (input.type === 'color') {
                input.addEventListener('input', () => updatePreview(component));
                input.addEventListener('change', () => updatePreview(component));
            } else {
                input.addEventListener('input', () => updatePreview(component));
            }
        });
    }
    
    function updatePreview(component) {
        // Get current property values from the form
        const properties = getCurrentPropertyValues();
        
        // Update the component preview in real-time
        updateComponentPreview(component, properties);
    }
    
    function getCurrentPropertyValues() {
        console.log('Getting current property values...');
        const properties = {};
        
        // Text component properties
        let textContent, textAlignment, textSize, textBold, textItalic, textUnderline;
        try {
            textContent = document.getElementById('text-content');
            textAlignment = document.getElementById('text-alignment');
            textSize = document.getElementById('text-size');
            textBold = document.getElementById('text-bold');
            textItalic = document.getElementById('text-italic');
            textUnderline = document.getElementById('text-underline');
        } catch(error) {
            console.error('Error getting text component elements:', error);
            showToast('Error getting text component elements. Please try again.', 'error');
        }
        
        try {
            if (textContent) properties.content = textContent.value;
            if (textAlignment) properties.alignment = textAlignment.value;
            if (textSize) properties.size = textSize.value;
            if (textBold) properties.bold = textBold.checked;
            if (textItalic) properties.italic = textItalic.checked;
            if (textUnderline) properties.underline = textUnderline.checked;
        } catch(error) {
            console.error('Error getting text component properties:', error);
            showToast('Error getting text component properties. Please try again.', 'error');
        }
        
        // Button component properties
        let buttonText, buttonUrl, buttonBgColor, buttonBgColorValue, buttonTextColor,
            buttonTextColorValue, buttonBorderColor, buttonBorderColorValue, buttonBorderWidth,
            buttonBorderRadius, buttonPadding, buttonFontSize, buttonFontWeight;
            
        try {
            buttonText = document.getElementById('button-text');
            buttonUrl = document.getElementById('button-url');
            buttonBgColor = document.getElementById('button-bg-color');
            buttonBgColorValue = document.getElementById('button-bg-color-value');
            buttonTextColor = document.getElementById('button-text-color');
            buttonTextColorValue = document.getElementById('button-text-color-value');
            buttonBorderColor = document.getElementById('button-border-color');
            buttonBorderColorValue = document.getElementById('button-border-color-value');
            buttonBorderWidth = document.getElementById('button-border-width');
            buttonBorderRadius = document.getElementById('button-border-radius');
            buttonPadding = document.getElementById('button-padding');
            buttonFontSize = document.getElementById('button-font-size');
            buttonFontWeight = document.getElementById('button-font-weight');
        } catch(error) {
            console.error('Error getting button component elements:', error);
            showToast('Error getting button component elements. Please try again.', 'error');
        }
        
        try {
            if (buttonText) properties.text = buttonText.value;
            if (buttonUrl) properties.url = buttonUrl.value;
            if (buttonBgColor) properties.backgroundColor = buttonBgColor.value;
            if (buttonBgColorValue) properties.backgroundColor = buttonBgColorValue.value;
            if (buttonTextColor) properties.textColor = buttonTextColor.value;
            if (buttonTextColorValue) properties.textColor = buttonTextColorValue.value;
            if (buttonBorderColor) properties.borderColor = buttonBorderColor.value;
            if (buttonBorderColorValue) properties.borderColor = buttonBorderColorValue.value;
            if (buttonBorderWidth) properties.borderWidth = buttonBorderWidth.value;
            if (buttonBorderRadius) properties.borderRadius = buttonBorderRadius.value;
            if (buttonPadding) properties.padding = buttonPadding.value;
            if (buttonFontSize) properties.fontSize = buttonFontSize.value;
            if (buttonFontWeight) properties.fontWeight = buttonFontWeight.value;
        } catch(error) {
            console.error('Error getting button component properties:', error);
            showToast('Error getting button component properties. Please try again.', 'error');
        }
        
        // Image component properties
        let imageSrc, imageAlt, imageWidth, imageHeight;
        try {
            imageSrc = document.getElementById('image-src');
            imageAlt = document.getElementById('image-alt');
            imageWidth = document.getElementById('image-width');
            imageHeight = document.getElementById('image-height');
        } catch(error) {
            console.error('Error getting image component elements:', error);
            showToast('Error getting image component elements. Please try again.', 'error');
        }
        
        try {
            if (imageSrc) properties.src = imageSrc.value;
            if (imageAlt) properties.alt = imageAlt.value;
            if (imageWidth) properties.width = imageWidth.value;
            if (imageHeight) properties.height = imageHeight.value;
        } catch(error) {
            console.error('Error getting image component properties:', error);
            showToast('Error getting image component properties. Please try again.', 'error');
        }
        
        // Link component properties
        let linkText, linkUrl, linkTarget, linkTextColor, linkTextColorValue, 
            linkFontSize, linkFontWeight, linkTextDecoration;
        try {
            linkText = document.getElementById('link-text');
            linkUrl = document.getElementById('link-url');
            linkTarget = document.getElementById('link-target');
            linkTextColor = document.getElementById('link-text-color');
            linkTextColorValue = document.getElementById('link-text-color-value');
            linkFontSize = document.getElementById('link-font-size');
            linkFontWeight = document.getElementById('link-font-weight');
            linkTextDecoration = document.getElementById('link-text-decoration');
        } catch(error) {
            console.error('Error getting link component elements:', error);
            showToast('Error getting link component elements. Please try again.', 'error');
        }
        
        try {
            if (linkText) properties.text = linkText.value;
            if (linkUrl) properties.url = linkUrl.value;
            if (linkTarget) properties.target = linkTarget.value;
            if (linkTextColor) properties.textColor = linkTextColor.value;
            if (linkTextColorValue) properties.textColor = linkTextColorValue.value;
            if (linkFontSize) properties.fontSize = linkFontSize.value;
            if (linkFontWeight) properties.fontWeight = linkFontWeight.value;
            if (linkTextDecoration) properties.textDecoration = linkTextDecoration.value;
        } catch(error) {
            console.error('Error getting link component properties:', error);
            showToast('Error getting link component properties. Please try again.', 'error');
        }
        
        // Divider component properties
        let dividerStyle, dividerColor, dividerThickness;
        try {
            dividerStyle = document.getElementById('divider-style');
            dividerColor = document.getElementById('divider-color');
            dividerThickness = document.getElementById('divider-thickness');
        } catch(error) {
            console.error('Error getting divider component elements:', error);
            showToast('Error getting divider component elements. Please try again.', 'error');
        }
        
        try {
            if (dividerStyle) properties.style = dividerStyle.value;
            if (dividerColor) properties.color = dividerColor.value;
            if (dividerThickness) properties.thickness = dividerThickness.value;
        } catch(error) {
            console.error('Error getting divider component properties:', error);
            showToast('Error getting divider component properties. Please try again.', 'error');
        }
        
        // Profile component properties
        let profileShowPhoto, profileShowName, profileShowUsername, profileAlignment, profileLayout, profilePhotoSize;
        try {
            profileShowPhoto = document.getElementById('profile-show-photo');
            profileShowName = document.getElementById('profile-show-name');
            profileShowUsername = document.getElementById('profile-show-username');
            profileAlignment = document.getElementById('profile-alignment');
            profileLayout = document.getElementById('profile-layout');
            profilePhotoSize = document.getElementById('profile-photo-size');
        } catch(error) {
            console.error('Error getting profile component elements:', error);
            showToast('Error getting profile component elements. Please try again.', 'error');
        }
        
        try {
            if (profileShowPhoto) properties.showPhoto = profileShowPhoto.checked;
            if (profileShowName) properties.showName = profileShowName.checked;
            if (profileShowUsername) properties.showUsername = profileShowUsername.checked;
            if (profileAlignment) properties.alignment = profileAlignment.value;
            if (profileLayout) properties.layout = profileLayout.value;
            if (profilePhotoSize) properties.photoSize = profilePhotoSize.value;
        } catch(error) {
            console.error('Error getting profile component properties:', error);
            showToast('Error getting profile component properties. Please try again.', 'error');
        }
        
        // Template component properties
        try {
            console.log('Getting template properties...');
            const templateImage = document.getElementById('template-image');
            const templateTitle = document.getElementById('template-title');
            const templateDescription = document.getElementById('template-description');
            const templatePrice = document.getElementById('template-price');
            const templateButtonText = document.getElementById('template-button-text');
            
            if (templateImage) {
                console.log('Template image:', templateImage.value);
                properties.image = templateImage.value;
            }
            if (templateTitle) {
                console.log('Template title:', templateTitle.value);
                properties.title = templateTitle.value;
            }
            if (templateDescription) {
                console.log('Template description:', templateDescription.value);
                properties.description = templateDescription.value;
            }
            if (templatePrice) {
                console.log('Raw template price:', templatePrice.value);
                // Convert formatted Rupiah string back to numeric value
                properties.price = parseInt(templatePrice.value.replace(/[^\d]/g, '')) || 0;
                console.log('Converted price:', properties.price);
            }
            if (templateButtonText) {
                console.log('Template button text:', templateButtonText.value);
                properties.buttonText = templateButtonText.value;
            }
            
            // Check if this is an empty template
            const componentElement = document.querySelector(`.component-wrapper[data-id="${currentComponentId}"]`);
            if (componentElement) {
                const existingProps = JSON.parse(componentElement.dataset.properties || '{}');
                if (existingProps.isEmpty === true) {
                    properties.isEmpty = true;
                }
            }
        } catch (error) {
            console.error('Error getting template properties:', error);
            showToast('Error getting template properties. Please try again.', 'error');
        }
        
        // Get existing digital product info if it exists
        try {
            const componentElement = document.querySelector(`.component-wrapper[data-id="${currentComponentId}"]`);
            if (componentElement) {
                const existingProps = JSON.parse(componentElement.dataset.properties || '{}');
                if (existingProps.digitalProduct) {
                    properties.digitalProduct = existingProps.digitalProduct;
                }
            }
        } catch (error) {
            console.error('Error getting digital product properties:', error);
            showToast('Error getting digital product properties. Please try again.', 'error');
        }
        
        return properties;
    }
    
    function updateComponentPreview(component, properties) {
        // Update the component preview without saving to server
        const componentElement = document.querySelector(`.component-wrapper[data-id="${component.dataset.id}"]`);
        if (!componentElement) return;
        
        const contentElement = componentElement.querySelector('.component-content');
        if (!contentElement) return;
        
        switch(component.dataset.type) {
            case 'text':
                // Build CSS classes for text formatting
                let textClasses = '';
                if (properties.alignment) {
                    textClasses += ` text-${properties.alignment}`;
                }
                if (properties.size) {
                    textClasses += ` ${properties.size}`;
                }
                if (properties.bold) {
                    textClasses += ' font-bold';
                }
                if (properties.italic) {
                    textClasses += ' italic';
                }
                if (properties.underline) {
                    textClasses += ' underline';
                }
                
                contentElement.innerHTML = `<div class="text-content${textClasses}">${properties.content || ''}</div>`;
                break;
            case 'button':
                // Build button styles
                let buttonClasses = '';
                let buttonStyles = '';
                
                // Add padding classes
                if (properties.padding) {
                    buttonClasses += ` ${properties.padding}`;
                }
                
                // Add font size and weight classes
                if (properties.fontSize) {
                    buttonClasses += ` ${properties.fontSize}`;
                }
                if (properties.fontWeight) {
                    buttonClasses += ` ${properties.fontWeight}`;
                }
                
                // Add border radius classes
                if (properties.borderRadius && properties.borderRadius !== 'none') {
                    buttonClasses += ` ${properties.borderRadius}`;
                }
                
                // Add border styles
                if (properties.borderWidth && properties.borderWidth !== '0') {
                    buttonStyles += `border: ${properties.borderWidth}px solid ${properties.borderColor || '#00c499'};`;
                }
                
                // Add background and text color styles
                buttonStyles += `background-color: ${properties.backgroundColor || '#00c499'};`;
                buttonStyles += `color: ${properties.textColor || '#ffffff'};`;
                
                contentElement.innerHTML = `<a href="${properties.url || '#'}" class="${buttonClasses}" style="${buttonStyles}">${properties.text || 'Button'}</a>`;
                break;
            case 'image':
                // Tambahkan penanganan width dan height
                const width = properties.width || '100%';
                const height = properties.height || 'auto';
                contentElement.innerHTML = `
                    <div class="resizable-image-container" style="position: relative; display: inline-block; width: ${width}; height: ${height};">
                        <img src="${properties.src || 'https://placehold.co/400x200'}" alt="${properties.alt || 'Image'}" class="resizable-image-element" style="width: 100%; height: 100%; display: block;">
                    </div>
                `;
                // Add resize handles
                setTimeout(() => {
                    const componentElement = contentElement.closest('.component-wrapper');
                    if (componentElement) {
                        addResizeHandles(componentElement);
                    }
                }, 0);
                break;
            case 'link':
                // Tambahkan penanganan target
                const target = properties.target === '_blank' ? 'target="_blank" rel="noopener noreferrer"' : '';
                
                // Build link styles
                let linkClasses = '';
                let linkStyles = '';
                
                // Add font size and weight classes
                if (properties.fontSize) {
                    linkClasses += ` ${properties.fontSize}`;
                }
                if (properties.fontWeight) {
                    linkClasses += ` ${properties.fontWeight}`;
                }
                
                // Add text decoration class
                if (properties.textDecoration && properties.textDecoration !== 'no-underline') {
                    linkClasses += ` ${properties.textDecoration}`;
                } else if (properties.textDecoration === 'no-underline') {
                    // Remove underline if explicitly set to no-underline
                    linkClasses += ' no-underline';
                }
                
                // Add text color style
                linkStyles += `color: ${properties.textColor || '#00c499'};`;
                
                contentElement.innerHTML = `<a href="${properties.url || '#'}" ${target} class="${linkClasses}" style="${linkStyles}">${properties.text || 'Link'}</a>`;
                break;
            case 'divider':
                // Tambahkan penanganan style divider
                const thickness = properties.thickness || '1px';
                const color = properties.color || '#e5e7eb';
                const style = properties.style || 'solid';
                contentElement.innerHTML = `<hr style="border: ${thickness} ${style} ${color};">`;
                break;
            case 'template':
                // Check if this is an empty template
                if (properties.isEmpty === true) {
                    contentElement.innerHTML = `
                        <div class="max-w-sm rounded overflow-hidden shadow-lg bg-white border-2 border-dashed border-gray-300 cursor-pointer empty-template-component" onclick="handleEmptyTemplateClick(this)">
                            <div class="bg-gray-100 h-48 flex items-center justify-center">
                                <div class="text-center p-4">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Empty Template</h3>
                                    <p class="mt-1 text-sm text-gray-500">Click to add product</p>
                                </div>
                            </div>
                            <div class="px-6 py-4">
                                <div class="font-bold text-xl mb-2 text-gray-400">No Product</div>
                                <p class="text-gray-500 text-base">
                                    <a href="/cms/products" class="text-primary hover:underline">Add products in the product page</a>
                                </p>
                            </div>
                            <div class="px-6 pt-4 pb-2 flex justify-between items-center">
                                <span class="text-xl font-bold text-primary">Rp 0</span>
                                <button class="bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded">
                                    Add Product
                                </button>
                            </div>
                        </div>
                    `;
                } else {
                    contentElement.innerHTML = `
                        <div class="max-w-sm rounded overflow-hidden shadow-lg bg-white">
                            <img class="w-full h-48 object-cover" src="${properties.image || 'https://placehold.co/400x300'}" alt="${properties.title || 'Template Image'}">
                            <div class="px-6 py-4">
                                <div class="font-bold text-xl mb-2">${properties.title || 'Template Title'}</div>
                                <p class="text-gray-700 text-base">
                                    ${properties.description || 'Template description goes here.'}
                                </p>
                            </div>
                            <div class="px-6 pt-4 pb-2 flex justify-between items-center">
                                <span class="text-xl font-bold text-primary">Rp ${formatRupiahValue(properties.price || 0)}</span>
                                <button class="bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded">
                                    ${properties.buttonText || 'Buy Now'}
                                </button>
                            </div>
                        </div>
                    `;
                }
                break;
        }
    }
    
    function saveComponentProperties() {
        console.log('Saving component properties...');
        console.log('Current component ID:', currentComponentId);
        console.log('Current component type:', currentComponentType);
        
        if (!currentComponentId || !currentComponentType) {
            console.error('No component selected');
            showToast('No component selected. Please try again.', 'error');
            return;
        }
        
        try {
            // Get current property values (including any changes made in real-time preview)
            let properties = getCurrentPropertyValues();
            
            // Log the properties being saved for debugging
            console.log('Properties to be saved:', properties);
        
        // Update the component element's data-properties attribute
        const componentElement = document.querySelector(`.component-wrapper[data-id="${currentComponentId}"]`);
        if (componentElement) {
            componentElement.dataset.properties = JSON.stringify(properties);
        }
        
        // Extract digital_product_path from properties if exists
        let digitalProductPath = null;
        if (properties.digitalProduct && properties.digitalProduct.path) {
            digitalProductPath = properties.digitalProduct.path;
            // Remove digitalProduct from properties to avoid duplication
            // Keep it in properties for backward compatibility but also send separately
        }
        
        // Prepare request data
        const requestData = {
            properties: properties,
            order: 0, // We'll keep the same order for now
            digital_product_path: digitalProductPath
        };
        
        console.log('Sending request with data:', requestData);
        
        // Update the component on the server
        fetch(`/cms/builder/${domainId}/component/${currentComponentId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify(requestData)
        })
        .then(response => {
            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('Received non-JSON response');
            }
            
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Validasi data
            if (!data || !data.id) {
                throw new Error('Invalid response data');
            }
            
            // Update the component in the DOM
            updateComponentInDOM(data);
            closePropertiesPanel();
        })
        .catch(error => {
            console.error('Error updating component:', error);
            // Log additional error details
            if (error.response) {
                console.error('Response data:', error.response);
            }
            showToast('Error updating component. Please try again.', 'error');
        });
        } catch (error) {
            console.error('Error in saveComponentProperties:', error);
            showToast('Error preparing component data. Please try again.', 'error');
        }
    }
    
    function updateComponentInDOM(component) {
        // Validasi parameter
        if (!component || !component.id) {
            console.error('Invalid component data:', component);
            showToast('Invalid component data. Please try again.', 'error');
            return;
        }
        
        const componentElement = document.querySelector(`.component-wrapper[data-id="${component.id}"]`);
        if (!componentElement) {
            console.error('Component element not found for ID:', component.id);
            showToast('Component not found. Please try again.', 'error');
            return;
        }
        
        // Update the properties data attribute
        componentElement.dataset.properties = JSON.stringify(component.properties);
        componentElement.dataset.type = component.type;
        
        // Update digital_product_path if exists
        if (component.digital_product_path) {
            componentElement.dataset.digitalProductPath = component.digital_product_path;
        }
        
        // Update the component content
        const contentElement = componentElement.querySelector('.component-content');
        if (contentElement) {
            switch(component.type) {
                case 'text':
                    // Build CSS classes for text formatting
                    let textClasses = '';
                    if (component.properties.alignment) {
                        textClasses += ` text-${component.properties.alignment}`;
                    }
                    if (component.properties.size) {
                        textClasses += ` ${component.properties.size}`;
                    }
                    if (component.properties.bold) {
                        textClasses += ' font-bold';
                    }
                    if (component.properties.italic) {
                        textClasses += ' italic';
                    }
                    if (component.properties.underline) {
                        textClasses += ' underline';
                    }
                    
                    contentElement.innerHTML = `<div class="text-content${textClasses}">${component.properties.content || ''}</div>`;
                    break;
                case 'button':
                    // Build button styles
                    let buttonClasses = '';
                    let buttonStyles = '';
                    
                    // Add padding classes
                    if (component.properties.padding) {
                        buttonClasses += ` ${component.properties.padding}`;
                    }
                    
                    // Add font size and weight classes
                    if (component.properties.fontSize) {
                        buttonClasses += ` ${component.properties.fontSize}`;
                    }
                    if (component.properties.fontWeight) {
                        buttonClasses += ` ${component.properties.fontWeight}`;
                    }
                    
                    // Add border radius classes
                    if (component.properties.borderRadius && component.properties.borderRadius !== 'none') {
                        buttonClasses += ` ${component.properties.borderRadius}`;
                    }
                    
                    // Add border styles
                    if (component.properties.borderWidth && component.properties.borderWidth !== '0') {
                        buttonStyles += `border: ${component.properties.borderWidth}px solid ${component.properties.borderColor || '#00c499'};`;
                    }
                    
                    // Add background and text color styles
                    buttonStyles += `background-color: ${component.properties.backgroundColor || '#00c499'};`;
                    buttonStyles += `color: ${component.properties.textColor || '#ffffff'};`;
                    
                    contentElement.innerHTML = `<a href="${component.properties.url || '#'}" class="${buttonClasses}" style="${buttonStyles}">${component.properties.text || 'Button'}</a>`;
                    break;
                case 'image':
                    // Tambahkan penanganan width dan height
                    const width = component.properties.width || '100%';
                    const height = component.properties.height || 'auto';
                    contentElement.innerHTML = `
                        <div class="resizable-image-container" style="position: relative; display: inline-block; width: ${width}; height: ${height};">
                            <img src="${component.properties.src || 'https://placehold.co/400x200'}" alt="${component.properties.alt || 'Image'}" class="resizable-image-element" style="width: 100%; height: 100%; display: block;">
                            <!-- Resize handles will be added dynamically -->
                        </div>
                    `;
                    // Add resize handles
                    setTimeout(() => {
                        addResizeHandles(componentElement);
                    }, 0);
                    break;
                case 'link':
                    // Tambahkan penanganan target
                    const target = component.properties.target === '_blank' ? 'target="_blank" rel="noopener noreferrer"' : '';
                    
                    // Build link styles
                    let linkClasses = '';
                    let linkStyles = '';
                    
                    // Add font size and weight classes
                    if (component.properties.fontSize) {
                        linkClasses += ` ${component.properties.fontSize}`;
                    }
                    if (component.properties.fontWeight) {
                        linkClasses += ` ${component.properties.fontWeight}`;
                    }
                    
                    // Add text decoration class
                    if (component.properties.textDecoration && component.properties.textDecoration !== 'no-underline') {
                        linkClasses += ` ${component.properties.textDecoration}`;
                    } else if (component.properties.textDecoration === 'no-underline') {
                        // Remove underline if explicitly set to no-underline
                        linkClasses += ' no-underline';
                    }
                    
                    // Add text color style
                    linkStyles += `color: ${component.properties.textColor || '#00c499'};`;
                    
                    contentElement.innerHTML = `<a href="${component.properties.url || '#'}" ${target} class="${linkClasses}" style="${linkStyles}">${component.properties.text || 'Link'}</a>`;
                    break;
                case 'divider':
                    // Tambahkan penanganan style divider
                    const thickness = component.properties.thickness || '1px';
                    const color = component.properties.color || '#e5e7eb';
                    const style = component.properties.style || 'solid';
                    contentElement.innerHTML = `<hr style="border: ${thickness} ${style} ${color};">`;
                    break;
                case 'profile':
                    // Build profile layout based on properties
                    const layout = component.properties.layout || 'horizontal';
                    const photoSize = component.properties.photoSize || 'medium';
                    const alignment = component.properties.alignment || 'left';
                    const showPhoto = component.properties.showPhoto !== false;
                    const showName = component.properties.showName !== false;
                    const showUsername = component.properties.showUsername !== false;
                    
                    // Photo size mapping
                    const sizeMap = {
                        'small': 'w-12 h-12',
                        'medium': 'w-16 h-16',
                        'large': 'w-24 h-24',
                        'xlarge': 'w-32 h-32'
                    };
                    const photoSizeClass = sizeMap[photoSize] || 'w-16 h-16';
                    
                    // Alignment classes
                    let alignmentClasses = '';
                    if (alignment === 'center') {
                        alignmentClasses = 'justify-center text-center';
                    } else if (alignment === 'right') {
                        alignmentClasses = 'justify-end text-right';
                    } else {
                        alignmentClasses = 'justify-start text-left';
                    }
                    
                    // Layout classes
                    let layoutClasses = 'flex items-center gap-4 p-4';
                    if (layout === 'vertical') {
                        layoutClasses = 'flex flex-col items-center gap-4 p-4 text-center';
                    } else if (layout === 'compact') {
                        layoutClasses = 'flex items-center gap-2 p-2';
                    } else if (layout === 'card') {
                        layoutClasses = 'flex flex-col items-center gap-4 p-6 bg-gray-50 rounded-lg border';
                    }
                    
                    let profileHtml = `<div class="${layoutClasses} ${alignmentClasses}">`;
                    
                    if (showPhoto) {
                        if (domainUser.profilePhoto) {
                            profileHtml += `<img src="${domainUser.profilePhoto}" alt="${domainUser.name}" class="${photoSizeClass} rounded-full object-cover">`;
                        } else {
                            profileHtml += `
                                <div class="${photoSizeClass} rounded-full bg-primary/10 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            `;
                        }
                    }
                    
                    if (showName || showUsername) {
                        profileHtml += '<div>';
                        if (showName) {
                            profileHtml += `<h3 class="font-semibold text-lg text-gray-900">${domainUser.name}</h3>`;
                        }
                        if (showUsername) {
                            profileHtml += `<p class="text-gray-600 text-sm">@${domainUser.slug}</p>`;
                        }
                        profileHtml += '</div>';
                    }
                    
                    profileHtml += '</div>';
                    contentElement.innerHTML = profileHtml;
                    break;
                case 'template':
                    try {
                        const price = component.properties.price ? parseInt(component.properties.price.toString().replace(/[^\d]/g, '')) : 0;
                        contentElement.innerHTML = `
                            <div class="max-w-sm rounded overflow-hidden shadow-lg bg-white">
                                <img class="w-full h-48 object-cover" src="${component.properties.image || 'https://placehold.co/400x300'}" alt="${component.properties.title || 'Template Image'}">
                                <div class="px-6 py-4">
                                    <div class="font-bold text-xl mb-2">${component.properties.title || 'Template Title'}</div>
                                    <p class="text-gray-700 text-base">
                                        ${component.properties.description || 'Template description goes here.'}
                                    </p>
                                </div>
                                <div class="px-6 pt-4 pb-2 flex justify-between items-center">
                                    <span class="text-xl font-bold text-primary">Rp ${formatRupiahValue(price)}</span>
                                    <a href="/checkout/${domainId}/${component.id}" class="bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded no-underline">
                                        ${component.properties.buttonText || 'Buy Now'}
                                    </a>
                                </div>
                            </div>
                        `;
                    } catch (error) {
                        console.error('Error updating template component:', error);
                        showToast('Error updating template component. Please try again.', 'error');
                    }
                    break;
                default:
                    // Handle unknown component types gracefully
                    contentElement.innerHTML = `<div class="text-red-500 p-2">Unknown component type: ${component.type || 'undefined'}</div>`;
                    console.warn('Unknown component type:', component.type);
                    // Don't show error toast for unknown types to avoid confusion
            }
        }
        
        showToast('Component updated successfully!', 'success');
    }
    
    function deleteComponent(componentId) {
        // Validasi parameter
        if (!componentId) {
            console.error('Invalid component ID');
            showToast('Invalid component ID. Please try again.', 'error');
            return;
        }
        
        // Remove from server
        fetch(`/cms/builder/${domainId}/component/${componentId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('Received non-JSON response');
            }
            
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Remove from DOM
            const componentElement = document.querySelector(`.component-wrapper[data-id="${componentId}"]`);
            if (componentElement) {
                componentElement.remove();
                showToast('Component deleted successfully!', 'success');
            } else {
                // Jika elemen tidak ditemukan, tetap tampilkan pesan sukses
                showToast('Component deleted successfully!', 'success');
            }
            
            // Check if canvas is empty and show empty message
            const canvas = document.getElementById('canvas');
            if (canvas && canvas.children.length === 0) {
                const emptyMessage = `
                    <div id="empty-canvas-message" class="text-center py-12 text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No components yet</h3>
                        <p class="mt-1 text-sm text-gray-500">Drag components here from the left panel</p>
                    </div>
                `;
                canvas.innerHTML = emptyMessage;
            }
        })
        .catch(error => {
            console.error('Error deleting component:', error);
            showToast('Error deleting component. Please try again.', 'error');
        });
    }
    
    function closePropertiesPanel() {
        const propertiesPanel = document.getElementById('properties-panel');
        if (propertiesPanel) {
            propertiesPanel.classList.add('hidden');
        }
        currentComponentId = null;
        currentComponentType = null;
    }
    
    function previewPage() {
        try {
            // Create a clean copy of the canvas for preview
            const canvas = document.getElementById('canvas');
            const previewContent = document.getElementById('preview-content');
            
            // Clear previous preview content
            previewContent.innerHTML = '';
            
            // Clone all component wrappers
            const components = canvas.querySelectorAll('.component-wrapper');
            components.forEach(component => {
                // Clone the component
                const clone = component.cloneNode(true);
                
                // Remove edit buttons from clone
                const editButtons = clone.querySelectorAll('.group-hover\\:opacity-100');
                editButtons.forEach(button => button.remove());
                
                // Add to preview
                previewContent.appendChild(clone);
            });
            
            // Show the preview modal
            document.getElementById('preview-modal').classList.remove('hidden');
            document.getElementById('preview-modal').classList.add('flex');
        } catch (error) {
            console.error('Error generating preview:', error);
            showToast('Error generating preview. Please try again.', 'error');
        }
    }
    
    function closePreview() {
        const previewModal = document.getElementById('preview-modal');
        if (previewModal) {
            previewModal.classList.add('hidden');
            previewModal.classList.remove('flex');
        }
    }

    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toast-message');
        const icon = toast.querySelector('svg');
        const wrapper = toast.querySelector('.flex');
        
        // Validasi elemen
        if (!toast || !toastMessage || !icon || !wrapper) {
            console.error('Toast elements not found');
            return;
        }
        
        // Reset classes
        wrapper.className = 'flex items-center p-4 text-sm rounded-lg shadow-lg min-w-[300px] max-w-md';
        icon.innerHTML = '';
        
        // Set style based on type
        if (type === 'success') {
            wrapper.classList.add('bg-green-50', 'text-green-800');
            icon.innerHTML = '<path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/>';
        } else if (type === 'error') {
            wrapper.classList.add('bg-red-50', 'text-red-800');
            icon.innerHTML = '<path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293 2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/>';
        } else if (type === 'info') {
            wrapper.classList.add('bg-blue-50', 'text-blue-800');
            icon.innerHTML = '<path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm0 4.5a1 1 0 1 1 0 2 1 1 0 0 1 0-2Zm1 4.5a1 1 0 0 1-1 1H9a1 1 0 0 1-1-1v-5a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v5Z"/>';
        }
        
        toastMessage.textContent = message;
        toast.classList.remove('translate-x-full');
        toast.classList.add('translate-x-0');
        
        // Auto hide after 3 seconds
        setTimeout(hideToast, 3000);
    }

    function hideToast() {
        const toast = document.getElementById('toast');
        if (toast) {
            toast.classList.remove('translate-x-0');
            toast.classList.add('translate-x-full');
        }
    }
    
    // Fungsi untuk membuka modal template
    function openTemplateModal() {
        const modal = document.getElementById('template-modal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            loadTemplateProducts();
        }
    }
    
    // Fungsi untuk menutup modal template
    function closeTemplateModal() {
        const modal = document.getElementById('template-modal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }
    
    function loadTemplateProducts() {
        const container = document.getElementById('template-products-container');
        if (!container) return;
        
        fetch(`/cms/builder/${domainId}/template-products`)
            .then(response => response.json())
            .then(data => {
                if (data.length === 0) {
                    container.innerHTML = `
                        <div class="col-span-full text-center py-8">
                            <p class="text-gray-500">No template products found.</p>
                            <a href="/cms/products" class="mt-2 inline-block text-primary hover:underline">Create your first template product</a>
                        </div>
                    `;
                    return;
                }
                
                container.innerHTML = '';
                data.forEach(product => {
                    const productElement = createTemplateProductElement(product);
                    container.appendChild(productElement);
                });
            })
            .catch(error => {
                console.error('Error loading template products:', error);
                container.innerHTML = `
                    <div class="col-span-full text-center py-8 text-red-500">
                        <p>Error loading template products. Please try again.</p>
                    </div>
                `;
            });
    }
    
    function createTemplateProductElement(product) {
        const element = document.createElement('div');
        element.className = 'border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow cursor-pointer';
        element.onclick = () => addTemplateToBuilder(product);
        
        // Format harga
        const price = product.properties.price ? parseInt(product.properties.price.toString().replace(/[^\d]/g, '')) : 0;
        const formattedPrice = formatRupiahValue(price);
        
        element.innerHTML = `
            <div class="relative">
                <img src="${product.properties.image || 'https://placehold.co/400x300'}" 
                     alt="${product.properties.title || 'Template Product'}" 
                     class="w-full h-40 object-cover">
                ${product.digital_product_path ? 
                    `<span class="absolute top-2 right-2 bg-green-500 text-white text-xs px-2 py-1 rounded">Digital</span>` : 
                    ''}
            </div>
            <div class="p-4">
                <h3 class="font-semibold text-gray-900 truncate">${product.properties.title || 'Untitled Product'}</h3>
                <p class="text-gray-600 text-sm mt-1 line-clamp-2">${product.properties.description || 'No description'}</p>
                <div class="mt-3 flex justify-between items-center">
                    <span class="text-lg font-bold text-primary">Rp ${formattedPrice}</span>
                    <button class="px-3 py-1 bg-primary hover:bg-primary-dark text-white text-sm rounded">
                        Add to Builder
                    </button>
                </div>
            </div>
        `;
        
        return element;
    }
    
    function addTemplateToBuilder(product) {
        // Jika ada fungsi kustom yang diatur (untuk template kosong), gunakan itu
        if (window.addTemplateToBuilder && window.addTemplateToBuilder !== addTemplateToBuilder) {
            window.addTemplateToBuilder(product);
            return;
        }
        
        // Untuk template biasa, tambahkan ke canvas
        const emptyMessage = document.getElementById('empty-canvas-message');
        if (emptyMessage) {
            emptyMessage.remove();
        }
        
        // Buat komponen di server
        fetch(`/cms/builder/${domainId}/add-template`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                template_id: product.id
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                throw new Error(data.error);
            }
            
            // Tambahkan ke canvas
            const componentWrapper = document.createElement('div');
            componentWrapper.className = 'component-wrapper relative group border border-transparent hover:border-dashed hover:border-gray-300 rounded p-2';
            componentWrapper.dataset.id = data.id;
            componentWrapper.dataset.type = data.type;
            componentWrapper.dataset.properties = JSON.stringify(data.properties);
            if (data.digital_product_path) {
                componentWrapper.dataset.digitalProductPath = data.digital_product_path;
            }
            
            // Format harga untuk ditampilkan
            const price = data.properties.price ? parseInt(data.properties.price.toString().replace(/[^\d]/g, '')) : 0;
            const formattedPrice = formatRupiahValue(price);
            
            componentWrapper.innerHTML = `
                <div class="component-content">
                    <div class="max-w-sm rounded overflow-hidden shadow-lg bg-white">
                        <img class="w-full h-48 object-cover" src="${data.properties.image || 'https://placehold.co/400x300'}" alt="${data.properties.title || 'Template Image'}">
                        <div class="px-6 py-4">
                            <div class="font-bold text-xl mb-2">${data.properties.title || 'Template Title'}</div>
                            <p class="text-gray-700 text-base">
                                ${data.properties.description || 'Template description goes here.'}
                            </p>
                        </div>
                        <div class="px-6 pt-4 pb-2 flex justify-between items-center">
                            <span class="text-xl font-bold text-primary">Rp ${formattedPrice}</span>
                            <a href="/checkout/${domainId}/${data.id}" class="bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded no-underline">
                                ${data.properties.buttonText || 'Buy Now'}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="absolute top-0 right-0 opacity-0 group-hover:opacity-100 transition-opacity flex gap-1 p-1 bg-white rounded shadow">
                    <button onclick="editComponent(${data.id})" class="p-1 text-gray-500 hover:text-blue-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </button>
                    <button onclick="deleteComponent(${data.id})" class="p-1 text-gray-500 hover:text-red-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            `;
            
            document.getElementById('canvas').appendChild(componentWrapper);
            closeTemplateModal();
            showToast('Template product added to builder!', 'success');
        })
        .catch(error => {
            console.error('Error adding template to builder:', error);
            showToast('Error adding template product. Please try again.', 'error');
        });
    }
    
    // Toggle Components Panel (Mobile)
    function toggleComponentsPanel() {
        const panel = document.getElementById('components-panel');
        const overlay = document.getElementById('panel-overlay');
        
        if (panel && overlay) {
            panel.classList.toggle('open');
            overlay.classList.toggle('active');
        }
    }
    
    // Close Mobile Panels
    function closeMobilePanels() {
        const componentsPanel = document.getElementById('components-panel');
        const propertiesPanel = document.getElementById('properties-panel');
        const overlay = document.getElementById('panel-overlay');
        
        if (componentsPanel) componentsPanel.classList.remove('open');
        if (propertiesPanel) propertiesPanel.classList.remove('open');
        if (overlay) overlay.classList.remove('active');
    }
    
    // Override closePropertiesPanel to handle mobile
    const originalClosePropertiesPanel = closePropertiesPanel;
    closePropertiesPanel = function() {
        const panel = document.getElementById('properties-panel');
        const overlay = document.getElementById('panel-overlay');
        
        if (panel) panel.classList.add('hidden');
        if (overlay) overlay.classList.remove('active');
    };
    
    // Override editComponent to show overlay on mobile
    const originalEditComponent = editComponent;
    editComponent = function(componentId) {
        originalEditComponent(componentId);
        
        // Show overlay on mobile when properties panel opens
        const overlay = document.getElementById('panel-overlay');
        if (overlay && window.innerWidth < 768) {
            overlay.classList.add('active');
        }
    };
    
    // Toggle Device View (Desktop/Mobile)
    function toggleDeviceView(device) {
        const canvasContainer = document.getElementById('canvas-container');
        const desktopBtn = document.getElementById('desktop-view-btn');
        const mobileBtn = document.getElementById('mobile-view-btn');
        
        if (device === 'mobile') {
            canvasContainer.classList.add('mobile-view');
            canvasContainer.classList.add('mobile-frame');
            
            // Update button styles
            desktopBtn.classList.remove('bg-primary', 'text-white');
            desktopBtn.classList.add('bg-white', 'hover:bg-gray-50');
            mobileBtn.classList.remove('bg-white', 'hover:bg-gray-50');
            mobileBtn.classList.add('bg-primary', 'text-white');
        } else {
            canvasContainer.classList.remove('mobile-view');
            canvasContainer.classList.remove('mobile-frame');
            
            // Update button styles
            mobileBtn.classList.remove('bg-primary', 'text-white');
            mobileBtn.classList.add('bg-white', 'hover:bg-gray-50');
            desktopBtn.classList.remove('bg-white', 'hover:bg-gray-50');
            desktopBtn.classList.add('bg-primary', 'text-white');
        }
    }
    
    function publishPage() {
        fetch(`/cms/builder/${domainId}/publish`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('Received non-JSON response');
            }
            
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Validasi response
            if (data && data.message) {
                showToast('Page published successfully!', 'success');
            } else {
                showToast('Page published successfully!', 'success');
            }
        })
        .catch(error => {
            console.error('Error publishing page:', error);
            showToast('Error publishing page. Please try again.', 'error');
        });
    }
    
    // Component reordering functions
    // Note: These functions are deprecated as we're now using SortableJS
    // let draggedComponent = null;
    
    // function componentDragStart(e) {
    //     // Deprecated - using SortableJS instead
    // }
    
    // function componentDragOver(e) {
    //     // Deprecated - using SortableJS instead
    // }
    
    // function componentDragEnter(e) {
    //     // Deprecated - using SortableJS instead
    // }
    
    // function componentDragLeave(e) {
    //     // Deprecated - using SortableJS instead
    // }
    
    // function componentDrop(e) {
    //     // Deprecated - using SortableJS instead
    // }
    
    // function componentDragEnd(e) {
    //     // Deprecated - using SortableJS instead
    // }
    
    function reorderComponents() {
        const canvas = document.getElementById('canvas');
        const components = Array.from(canvas.children);
        const componentData = components.map((component, index) => {
            // Pastikan komponen memiliki ID yang valid
            const id = component.dataset.id;
            if (!id) {
                console.warn('Component without ID found:', component);
                return null;
            }
            
            // Exclude unsaved components (those with temp IDs)
            if (id.startsWith('temp_')) {
                return null;
            }
            
            return {
                id: id,
                order: index
            };
        }).filter(component => component !== null); // Remove null entries
        
        // Hanya kirim request jika ada komponen yang perlu diurutkan
        if (componentData.length > 0) {
            fetch(`/cms/builder/${domainId}/reorder`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    components: componentData
                })
            })
            .then(response => {
                // Check if response is JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('Received non-JSON response: ' + response.status + ' ' + response.statusText);
                }
                
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status + ' ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                // Validasi response
                showToast('Components reordered successfully!', 'success');
            })
            .catch(error => {
                console.error('Error reordering components:', error);
                showToast('Error reordering components. Please try again.', 'error');
            });
        }
    }
    
    // Reorder components when dragged
    // This would be implemented with a proper drag-and-drop library in a real application
    
    function switchImageTab(tab) {
        // Switch active tab button
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('active', 'bg-gray-100');
        });
        
        if (tab === 'url') {
            document.querySelector('[onclick="switchImageTab(\'url\')"]').classList.add('active', 'bg-gray-100');
            document.getElementById('url-tab').classList.remove('hidden');
            document.getElementById('upload-tab').classList.add('hidden');
        } else {
            document.querySelector('[onclick="switchImageTab(\'upload\')"]').classList.add('active', 'bg-gray-100');
            document.getElementById('url-tab').classList.add('hidden');
            document.getElementById('upload-tab').classList.remove('hidden');
        }
    }
    
    function switchTemplateImageTab(tab) {
        // Switch active tab button
        const tabButtons = document.querySelector('#properties-content').querySelectorAll('.tab-button');
        tabButtons.forEach(button => {
            button.classList.remove('active', 'bg-gray-100');
        });
        
        if (tab === 'url') {
            document.querySelector('[onclick="switchTemplateImageTab(\'url\')"]').classList.add('active', 'bg-gray-100');
            document.getElementById('template-url-tab').classList.remove('hidden');
            document.getElementById('template-upload-tab').classList.add('hidden');
        } else {
            document.querySelector('[onclick="switchTemplateImageTab(\'upload\')"]').classList.add('active', 'bg-gray-100');
            document.getElementById('template-url-tab').classList.add('hidden');
            document.getElementById('template-upload-tab').classList.remove('hidden');
        }
    }
    
    function uploadImageFile() {
        const fileInput = document.getElementById('image-upload');
        const file = fileInput.files[0];
        
        if (!file) {
            showToast('Please select an image file first.', 'error');
            return;
        }
        
        // Show progress
        document.getElementById('upload-progress').classList.remove('hidden');
        document.getElementById('upload-button').classList.add('hidden');
        
        const formData = new FormData();
        formData.append('image', file);
        
        // Create XMLHttpRequest for progress tracking
        const xhr = new XMLHttpRequest();
        
        // Update progress bar
        xhr.upload.addEventListener('progress', function(e) {
            if (e.lengthComputable) {
                const percentComplete = (e.loaded / e.total) * 100;
                document.getElementById('upload-progress-bar').style.width = percentComplete + '%';
            }
        });
        
        // Handle response
        xhr.addEventListener('load', function() {
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.url) {
                        // Update the image source field
                        document.getElementById('image-src').value = response.url;
                        showToast('Image uploaded successfully!', 'success');
                    } else {
                        showToast('Upload failed: ' + (response.error || 'Unknown error'), 'error');
                    }
                } catch (e) {
                    showToast('Upload failed: Invalid response', 'error');
                }
            } else {
                // Try to parse error response
                try {
                    const errorResponse = JSON.parse(xhr.responseText);
                    showToast('Upload failed: ' + (errorResponse.error || 'Server error'), 'error');
                } catch (e) {
                    showToast('Upload failed: Server error (HTTP ' + xhr.status + ')', 'error');
                }
            }
            
            // Hide progress
            document.getElementById('upload-progress').classList.add('hidden');
            document.getElementById('upload-button').classList.remove('hidden');
        });
        
        xhr.addEventListener('error', function() {
            showToast('Upload failed: Network error', 'error');
            document.getElementById('upload-progress').classList.add('hidden');
            document.getElementById('upload-button').classList.remove('hidden');
        });
        
        // Send request
        xhr.open('POST', `/cms/builder/${domainId}/upload-image`);
        // Set up CSRF token in headers (must be after open)
        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        xhr.send(formData);
    }
    
    function uploadTemplateImageFile() {
        const fileInput = document.getElementById('template-image-upload');
        const file = fileInput.files[0];
        
        if (!file) {
            showToast('Please select an image file first.', 'error');
            return;
        }
        
        // Show progress
        document.getElementById('template-upload-progress').classList.remove('hidden');
        document.getElementById('template-upload-button').classList.add('hidden');
        
        const formData = new FormData();
        formData.append('image', file);
        
        // Create XMLHttpRequest for progress tracking
        const xhr = new XMLHttpRequest();
        
        // Update progress bar
        xhr.upload.addEventListener('progress', function(e) {
            if (e.lengthComputable) {
                const percentComplete = (e.loaded / e.total) * 100;
                document.getElementById('template-upload-progress-bar').style.width = percentComplete + '%';
            }
        });
        
        // Handle response
        xhr.addEventListener('load', function() {
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.url) {
                        // Update the image source field
                        document.getElementById('template-image').value = response.url;
                        showToast('Image uploaded successfully!', 'success');
                    } else {
                        showToast('Upload failed: ' + (response.error || 'Unknown error'), 'error');
                    }
                } catch (e) {
                    showToast('Upload failed: Invalid response', 'error');
                }
            } else {
                try {
                    const errorResponse = JSON.parse(xhr.responseText);
                    showToast('Upload failed: ' + (errorResponse.error || 'Server error'), 'error');
                } catch (e) {
                    showToast('Upload failed: Server error (HTTP ' + xhr.status + ')', 'error');
                }
            }
            
            // Hide progress
            document.getElementById('template-upload-progress').classList.add('hidden');
            document.getElementById('template-upload-button').classList.remove('hidden');
        });
        
        xhr.addEventListener('error', function() {
            showToast('Upload failed: Network error', 'error');
            document.getElementById('template-upload-progress').classList.add('hidden');
            document.getElementById('template-upload-button').classList.remove('hidden');
        });
        
        // Send request
        xhr.open('POST', `/cms/builder/${domainId}/upload-image`);
        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        xhr.send(formData);
    }
    
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function handleDigitalProductSelect(input) {
        const file = input.files[0];
        const uploadButton = document.getElementById('digital-product-upload-button');
        
        if (file) {
            // Show file name and size
            const fileName = document.createElement('div');
            fileName.className = 'mt-2 text-sm text-gray-600';
            fileName.textContent = `Selected file: ${file.name} (${formatFileSize(file.size)})`;
            
            // Remove any existing file info
            const existingInfo = input.parentElement.querySelector('.selected-file-info');
            if (existingInfo) {
                existingInfo.remove();
            }
            
            fileName.classList.add('selected-file-info');
            input.parentElement.appendChild(fileName);
            
            // Show upload button
            uploadButton.classList.remove('hidden');
        } else {
            // Hide upload button if no file is selected
            uploadButton.classList.add('hidden');
        }
    }

    function uploadDigitalProduct() {
        const fileInput = document.getElementById('digital-product-upload');
        const file = fileInput.files[0];
        
        if (!file) {
            showToast('Please select a file first.', 'error');
            return;
        }
        
        // Show progress
        document.getElementById('digital-product-upload-progress').classList.remove('hidden');
        document.getElementById('digital-product-upload-button').classList.add('hidden');
        
        const formData = new FormData();
        formData.append('file', file);
        
        // Create XMLHttpRequest for progress tracking
        const xhr = new XMLHttpRequest();
        
        // Update progress bar
        xhr.upload.addEventListener('progress', function(e) {
            if (e.lengthComputable) {
                const percentComplete = (e.loaded / e.total) * 100;
                document.getElementById('digital-product-upload-progress-bar').style.width = percentComplete + '%';
            }
        });
        
        // Handle response
        xhr.addEventListener('load', function() {
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    
                    // Update the component's digital product properties
                    const componentElement = document.querySelector(`.component-wrapper[data-id="${currentComponentId}"]`);
                    if (componentElement) {
                        const properties = JSON.parse(componentElement.dataset.properties || '{}');
                        properties.digitalProduct = {
                            path: response.path,
                            originalName: response.originalName,
                            fileType: response.fileType,
                            fileSize: response.fileSize
                        };
                        componentElement.dataset.properties = JSON.stringify(properties);
                    }

                    // Show success message and update UI
                    document.getElementById('digital-product-info').classList.remove('hidden');
                    document.getElementById('digital-product-info').innerHTML = `
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Product file uploaded</span>
                        </div>
                        <p class="mt-1 text-xs">File size: ${formatFileSize(response.fileSize)}</p>
                    `;
                    showToast('Digital product uploaded successfully!', 'success');
                } catch (e) {
                    showToast('Upload failed: Invalid response', 'error');
                }
            } else {
                try {
                    const errorResponse = JSON.parse(xhr.responseText);
                    showToast('Upload failed: ' + (errorResponse.error || 'Server error'), 'error');
                } catch (e) {
                    showToast('Upload failed: Server error (HTTP ' + xhr.status + ')', 'error');
                }
            }
            
            // Hide progress
            document.getElementById('digital-product-upload-progress').classList.add('hidden');
            document.getElementById('digital-product-upload-button').classList.remove('hidden');
        });
        
        xhr.addEventListener('error', function() {
            showToast('Upload failed: Network error', 'error');
            document.getElementById('digital-product-upload-progress').classList.add('hidden');
            document.getElementById('digital-product-upload-button').classList.remove('hidden');
        });
        
        // Send request
        xhr.open('POST', `/cms/builder/${domainId}/upload-digital-product`);
        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        xhr.send(formData);
    }

    // Function to save empty template as a new product
    function saveEmptyTemplateAsProduct() {
        console.log('Saving empty template as product...');
        
        if (!currentComponentId) {
            showToast('No component selected. Please try again.', 'error');
            return;
        }
        
        // Get form values
        const title = document.getElementById('template-title').value.trim();
        const description = document.getElementById('template-description').value.trim();
        const price = document.getElementById('template-price').value;
        const image = document.getElementById('template-image').value;
        const buttonText = document.getElementById('template-button-text').value.trim();
        
        // Validate required fields
        if (!title) {
            showToast('Product name is required.', 'error');
            return;
        }
        
        if (!description) {
            showToast('Product description is required.', 'error');
            return;
        }
        
        if (!price || price === '0') {
            showToast('Product price is required.', 'error');
            return;
        }
        
        // Convert formatted price back to number
        const numericPrice = parseInt(price.replace(/[^\d]/g, '')) || 0;
        
        // Get digital product info if exists
        const componentElement = document.querySelector(`.component-wrapper[data-id="${currentComponentId}"]`);
        const properties = JSON.parse(componentElement.dataset.properties || '{}');
        const digitalProduct = properties.digitalProduct || null;
        
        // Prepare data
        const productData = {
            title: title,
            description: description,
            price: numericPrice,
            image: image,
            buttonText: buttonText || 'Buy Now',
            digital_product_path: digitalProduct ? digitalProduct.path : null,
            digital_product_original_name: digitalProduct ? digitalProduct.originalName : null,
            digital_product_file_type: digitalProduct ? digitalProduct.fileType : null,
            digital_product_file_size: digitalProduct ? digitalProduct.fileSize : null
        };
        
        console.log('Product data:', productData);
        
        // Show loading state
        const saveButton = document.querySelector('button[onclick="saveEmptyTemplateAsProduct()"]');
        const originalText = saveButton.textContent;
        saveButton.textContent = 'Creating Product...';
        saveButton.disabled = true;
        
        // Send request to create product and update template
        fetch(`/cms/builder/${domainId}/create-product-from-template`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                component_id: currentComponentId,
                product_data: productData
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Update the component with the new product data
                updateEmptyTemplateWithProduct(data.product, data.component);
                
                showToast('Product created successfully and template updated!', 'success');
                closePropertiesPanel();
            } else {
                throw new Error(data.error || 'Failed to create product');
            }
        })
        .catch(error => {
            console.error('Error creating product:', error);
            showToast('Error creating product: ' + error.message, 'error');
        })
        .finally(() => {
            // Restore button state
            saveButton.textContent = originalText;
            saveButton.disabled = false;
        });
    }
    
    // Function to update empty template with product data
    function updateEmptyTemplateWithProduct(product, component) {
        const componentElement = document.querySelector(`.component-wrapper[data-id="${component.id}"]`);
        if (!componentElement) return;
        
        // Update component properties
        componentElement.dataset.properties = JSON.stringify(component.properties);
        componentElement.dataset.type = component.type;
        
        // Update digital product path if exists
        if (component.digital_product_path) {
            componentElement.dataset.digitalProductPath = component.digital_product_path;
        }
        
        // Update the component content
        const contentElement = componentElement.querySelector('.component-content');
        if (contentElement) {
            const price = component.properties.price ? parseInt(component.properties.price.toString().replace(/[^\d]/g, '')) : 0;
            const formattedPrice = formatRupiahValue(price);
            
            contentElement.innerHTML = `
                <div class="max-w-sm rounded overflow-hidden shadow-lg bg-white">
                    <img class="w-full h-48 object-cover" src="${component.properties.image || 'https://placehold.co/400x300'}" alt="${component.properties.title || 'Template Image'}">
                    <div class="px-6 py-4">
                        <div class="font-bold text-xl mb-2">${component.properties.title || 'Template Title'}</div>
                        <p class="text-gray-700 text-base">
                            ${component.properties.description || 'Template description goes here.'}
                        </p>
                    </div>
                    <div class="px-6 pt-4 pb-2 flex justify-between items-center">
                        <span class="text-xl font-bold text-primary">Rp ${formattedPrice}</span>
                        <a href="/checkout/${domainId}/${component.id}" class="bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded no-underline">
                            ${component.properties.buttonText || 'Buy Now'}
                        </a>
                    </div>
                </div>
            `;
        }
        
        // Remove the empty template styling and make it look like a regular template
        componentElement.classList.remove('empty-template-component');
        componentElement.onclick = null; // Remove click handler
    }

    // Handle digital product file selection
    function handleDigitalProductFileSelect(input) {
        const file = input.files[0];
        const uploadButton = document.getElementById('digital-product-upload-button');
        const filenameElement = document.getElementById('digital-product-filename');
        
        if (file) {
            // Show filename and file size
            filenameElement.textContent = `Selected: ${file.name} (${formatFileSize(file.size)})`;
            
            // Show upload button
            uploadButton.classList.remove('hidden');
        } else {
            // Reset UI if no file selected
            filenameElement.textContent = 'No file chosen';
            uploadButton.classList.add('hidden');
        }
    }

    // Add event listener for file selection
    document.addEventListener('DOMContentLoaded', function() {
        // Add existing code...
        
        // Add event listener for image file selection
        document.addEventListener('change', function(e) {
            if (e.target && e.target.id === 'image-upload') {
                const file = e.target.files[0];
                if (file) {
                    document.getElementById('upload-filename').textContent = file.name;
                    document.getElementById('upload-button').classList.remove('hidden');
                } else {
                    document.getElementById('upload-filename').textContent = 'No file chosen';
                    document.getElementById('upload-button').classList.add('hidden');
                }
            } else if (e.target && e.target.id === 'template-image-upload') {
                const file = e.target.files[0];
                if (file) {
                    document.getElementById('template-upload-filename').textContent = file.name;
                    document.getElementById('template-upload-button').classList.remove('hidden');
                } else {
                    document.getElementById('template-upload-filename').textContent = 'No file chosen';
                    document.getElementById('template-upload-button').classList.add('hidden');
                }
            }
        });
        
        // Add event listeners for color pickers
        document.addEventListener('input', function(e) {
            // Button background color synchronization
            if (e.target && e.target.id === 'button-bg-color') {
                document.getElementById('button-bg-color-value').value = e.target.value;
            } else if (e.target && e.target.id === 'button-bg-color-value') {
                document.getElementById('button-bg-color').value = e.target.value;
            }
            // Button text color synchronization
            else if (e.target && e.target.id === 'button-text-color') {
                document.getElementById('button-text-color-value').value = e.target.value;
            } else if (e.target && e.target.id === 'button-text-color-value') {
                document.getElementById('button-text-color').value = e.target.value;
            }
            // Button border color synchronization
            else if (e.target && e.target.id === 'button-border-color') {
                document.getElementById('button-border-color-value').value = e.target.value;
            } else if (e.target && e.target.id === 'button-border-color-value') {
                document.getElementById('button-border-color').value = e.target.value;
            }
            // Link text color synchronization
            else if (e.target && e.target.id === 'link-text-color') {
                document.getElementById('link-text-color-value').value = e.target.value;
            } else if (e.target && e.target.id === 'link-text-color-value') {
                document.getElementById('link-text-color').value = e.target.value;
            }
        });
    });
    
    // Add resize handles to image components
    function addResizeHandles(componentElement) {
        const imageContainer = componentElement.querySelector('.resizable-image-container');
        if (!imageContainer) return;
        
        // Remove existing handles if any
        const existingHandles = imageContainer.querySelectorAll('.resize-handle');
        existingHandles.forEach(handle => handle.remove());
        
        // Create resize handles
        const handles = ['nw', 'n', 'ne', 'e', 'se', 's', 'sw', 'w'];
        handles.forEach(position => {
            const handle = document.createElement('div');
            handle.className = `resize-handle resize-handle-${position}`;
            handle.dataset.position = position;
            imageContainer.appendChild(handle);
        });
        
        // Add event listeners for resizing
        makeResizable(imageContainer);
    }
    
    // Make image container resizable
    function makeResizable(container) {
        const handles = container.querySelectorAll('.resize-handle');
        const image = container.querySelector('img');
        
        if (!handles.length || !image) return;
        
        let isResizing = false;
        let currentHandle = null;
        let startX, startY, startWidth, startHeight, startLeft, startTop;
        
        handles.forEach(handle => {
            handle.addEventListener('mousedown', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                isResizing = true;
                currentHandle = handle;
                
                startX = e.clientX;
                startY = e.clientY;
                startWidth = parseInt(document.defaultView.getComputedStyle(container).width, 10);
                startHeight = parseInt(document.defaultView.getComputedStyle(container).height, 10);
                startLeft = container.offsetLeft || 0;
                startTop = container.offsetTop || 0;
                
                // Add temporary class to indicate resizing
                container.classList.add('resizing');
                
                // Disable pointer events on other elements during resizing
                document.body.style.pointerEvents = 'none';
                container.style.pointerEvents = 'all';
                handle.style.pointerEvents = 'all';
                
                // Prevent text selection during resize
                document.body.style.userSelect = 'none';
            });
        });
        
        document.addEventListener('mousemove', function(e) {
            if (!isResizing) return;
            
            const dx = e.clientX - startX;
            const dy = e.clientY - startY;
            
            let newWidth = startWidth;
            let newHeight = startHeight;
            let newLeft = startLeft;
            let newTop = startTop;
            
            switch(currentHandle.dataset.position) {
                case 'se':
                    newWidth = startWidth + dx;
                    newHeight = startHeight + dy;
                    break;
                case 'sw':
                    newWidth = startWidth - dx;
                    newHeight = startHeight + dy;
                    newLeft = startLeft + dx;
                    break;
                case 'ne':
                    newWidth = startWidth + dx;
                    newHeight = startHeight - dy;
                    newTop = startTop + dy;
                    break;
                case 'nw':
                    newWidth = startWidth - dx;
                    newHeight = startHeight - dy;
                    newLeft = startLeft + dx;
                    newTop = startTop + dy;
                    break;
                case 'n':
                    newHeight = startHeight - dy;
                    newTop = startTop + dy;
                    break;
                case 's':
                    newHeight = startHeight + dy;
                    break;
                case 'e':
                    newWidth = startWidth + dx;
                    break;
                case 'w':
                    newWidth = startWidth - dx;
                    newLeft = startLeft + dx;
                    break;
            }
            
            // Ensure minimum size
            newWidth = Math.max(50, newWidth);
            newHeight = Math.max(50, newHeight);
            
            // Apply new dimensions
            container.style.width = newWidth + 'px';
            container.style.height = newHeight + 'px';
            
            // Only update position if it was initially set
            if (startLeft !== 0 || startTop !== 0) {
                container.style.left = newLeft + 'px';
                container.style.top = newTop + 'px';
            }
            
            // Update image to fit container
            image.style.width = '100%';
            image.style.height = '100%';
        });
        
        document.addEventListener('mouseup', function() {
            if (!isResizing) return;
            
            isResizing = false;
            container.classList.remove('resizing');
            
            // Re-enable pointer events
            document.body.style.pointerEvents = '';
            document.body.style.userSelect = '';
            
            // Update component properties with new dimensions
            const componentWrapper = container.closest('.component-wrapper');
            if (componentWrapper) {
                const properties = JSON.parse(componentWrapper.dataset.properties || '{}');
                properties.width = container.style.width;
                properties.height = container.style.height;
                componentWrapper.dataset.properties = JSON.stringify(properties);
                
                // Update width and height inputs in properties panel if it's open
                if (currentComponentId === componentWrapper.dataset.id) {
                    const widthInput = document.getElementById('image-width');
                    const heightInput = document.getElementById('image-height');
                    if (widthInput) widthInput.value = container.style.width;
                    if (heightInput) heightInput.value = container.style.height;
                }
            }
                });
    }
    
    // Template Product Selection Modal
    document.addEventListener('DOMContentLoaded', function() {
        // Buat modal template jika belum ada
        if (!document.getElementById('template-modal')) {
            const modalHTML = `
<!-- Template Product Selection Modal -->
<div id="template-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col">
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900">Select Template Product</h3>
            <button onclick="closeTemplateModal()" class="text-gray-400 hover:text-gray-500">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="p-6 overflow-y-auto flex-1">
            <div id="template-products-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Template products will be loaded here -->
                <div class="text-center py-8 text-gray-500">
                    <p>Loading template products...</p>
                </div>
            </div>
        </div>
        <div class="sticky bottom-0 bg-white border-t border-gray-200 px-6 py-4 flex justify-end">
            <button onclick="closeTemplateModal()" class="px-4 py-2 text-gray-700 hover:text-gray-900">
                Close
            </button>
        </div>
    </div>
</div>
`;
            // Tambahkan modal ke body
            document.body.insertAdjacentHTML('beforeend', modalHTML);
        }
    });
</script>
@endsection