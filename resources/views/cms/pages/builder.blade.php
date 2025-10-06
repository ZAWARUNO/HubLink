@extends('cms.layouts.app')

@section('content')
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
</style>

<div id="builder-app" class="flex flex-col h-full">
    <!-- Builder Header -->
    <div class="bg-white border-b p-4 flex justify-between items-center">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Page Builder</h1>
            <p class="text-gray-600">{{ $domain->title ?? 'hub.link/'.$domain->slug }}</p>
        </div>
        <div class="flex gap-2">
            <button onclick="previewPage()" class="px-4 py-2 border rounded-lg flex items-center gap-2">
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
        <div class="w-64 bg-white border-r flex flex-col">
            <div class="p-4 border-b">
                <h2 class="font-semibold text-gray-900">Components</h2>
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
            </div>
        </div>

        <!-- Canvas Area -->
        <div class="flex-1 flex flex-col">
            <div class="flex-1 overflow-auto p-8 bg-gray-50">
                <div class="max-w-2xl mx-auto bg-white min-h-full shadow-sm border rounded-lg p-6">
                    <div id="canvas" class="space-y-4">
                        <!-- Components will be added here -->
                        @if($components->count() == 0)
                            <div class="text-center py-12 text-gray-500">
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
                                 data-properties="{{ json_encode($component->properties) }}">
                                <div class="component-content">
                                    @switch($component->type)
                                        @case('text')
                                            <div class="text-content">
                                                {!! $component->properties['content'] ?? 'Text content' !!}
                                            </div>
                                            @break
                                        @case('button')
                                            <a href="{{ $component->properties['url'] ?? '#' }}" 
                                               class="px-4 py-2 bg-primary text-white rounded-lg block text-center">
                                                {{ $component->properties['text'] ?? 'Button' }}
                                            </a>
                                            @break
                                        @case('image')
                                            <img src="{{ $component->properties['src'] ?? 'https://placehold.co/400x200' }}" 
                                                 alt="{{ $component->properties['alt'] ?? 'Image' }}" 
                                                 class="max-w-full h-auto rounded">
                                            @break
                                        @case('link')
                                            <a href="{{ $component->properties['url'] ?? '#' }}" 
                                               class="text-primary underline">
                                                {{ $component->properties['text'] ?? 'Link text' }}
                                            </a>
                                            @break
                                        @case('divider')
                                            <hr class="border-gray-300">
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

<script>
    const domainId = {{ $domain->id }};
    let currentComponentId = null;
    let currentComponentType = null;
    
    document.addEventListener('DOMContentLoaded', function() {
        // Make components draggable
        document.querySelectorAll('.component-item').forEach(item => {
            item.addEventListener('dragstart', dragStart);
            item.addEventListener('dragend', dragEnd);
        });
        
        // Make canvas a drop zone
        const canvas = document.getElementById('canvas');
        if (canvas) {
            canvas.addEventListener('dragover', dragOver);
            canvas.addEventListener('dragenter', dragEnter);
            canvas.addEventListener('dragleave', dragLeave);
            canvas.addEventListener('drop', drop);
        }
        
        // Initialize SortableJS for component reordering
        if (typeof Sortable !== 'undefined' && canvas) {
            const sortable = new Sortable(canvas, {
                animation: 150,
                ghostClass: 'sortable-ghost',
                dragClass: 'sortable-drag',
                chosenClass: 'sortable-chosen',
                dragoverBubble: false,
                fallbackTolerance: 3,
                onEnd: function (evt) {
                    // Update order on server
                    reorderComponents();
                }
            });
        } else {
            console.warn('SortableJS not loaded or canvas not found');
        }
    });
    
    function dragStart(e) {
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
        
        e.dataTransfer.setData('text/plain', e.target.dataset.type);
        e.dataTransfer.effectAllowed = 'copy';
        
        // Add visual feedback
        e.target.classList.add('dragging');
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
        
        // Validasi event
        if (!e) {
            console.error('Invalid drop event');
            return;
        }
        
        const canvas = document.getElementById('canvas');
        if (canvas) {
            canvas.classList.remove('drag-over');
        }
        
        const type = e.dataTransfer.getData('text/plain');
        if (type) {
            addComponentToCanvas(type);
        }
    }
    
    function addComponentToCanvas(type) {
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
                    <img src="https://placehold.co/400x200" alt="Image" class="max-w-full h-auto rounded">
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
    
    function getDefaultProperties(type) {
        // Validasi parameter
        if (!type) {
            console.error('Invalid component type');
            return {};
        }
        
        switch(type) {
            case 'text':
                return { content: '<p>Edit this text content</p>' };
            case 'button':
                return { 
                    text: 'Button Text', 
                    url: '#',
                    style: 'primary'
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
                    target: '_blank'
                };
            case 'divider':
                return {
                    style: 'solid',
                    color: '#e5e7eb',
                    thickness: '1px'
                };
            default:
                console.warn('Unknown component type:', type);
                return {};
        }
    }
    
    function editComponent(componentId) {
        // Validasi parameter
        if (!componentId) {
            console.error('Invalid component ID');
            showToast('Invalid component ID. Please try again.', 'error');
            return;
        }
        
        const component = document.querySelector(`.component-wrapper[data-id="${componentId}"]`);
        if (!component) {
            console.error('Component not found:', componentId);
            showToast('Component not found. Please try again.', 'error');
            return;
        }
        
        currentComponentId = componentId;
        currentComponentType = component.dataset.type;
        
        // Show properties panel
        const propertiesPanel = document.getElementById('properties-panel');
        if (propertiesPanel) {
            propertiesPanel.classList.remove('hidden');
        }
        
        // Load properties based on component type
        loadProperties(component);
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
        
        let html = '';
        
        switch(component.dataset.type) {
            case 'text':
                html = `
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                            <textarea id="text-content" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary" rows="4">${properties.content || ''}</textarea>
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
                            <label class="block text-sm font-medium text-gray-700 mb-1">Style</label>
                            <select id="button-style" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="primary" ${properties.style === 'primary' ? 'selected' : ''}>Primary</option>
                                <option value="secondary" ${properties.style === 'secondary' ? 'selected' : ''}>Secondary</option>
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
                            <label class="block text-sm font-medium text-gray-700 mb-1">Image URL</label>
                            <input type="text" id="image-src" value="${properties.src || ''}" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
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
            default:
                html = '<p class="text-red-500">No properties to edit for this component type.</p>';
        }
        
        propertiesContent.innerHTML = html;
    }
    
    function saveComponentProperties() {
        if (!currentComponentId || !currentComponentType) {
            console.error('No component selected');
            showToast('No component selected. Please try again.', 'error');
            return;
        }
        
        let properties = {};
        
        switch(currentComponentType) {
            case 'text':
                const textContent = document.getElementById('text-content');
                if (textContent) {
                    properties.content = textContent.value;
                }
                break;
            case 'button':
                const buttonText = document.getElementById('button-text');
                const buttonUrl = document.getElementById('button-url');
                const buttonStyle = document.getElementById('button-style');
                if (buttonText && buttonUrl && buttonStyle) {
                    properties.text = buttonText.value;
                    properties.url = buttonUrl.value;
                    properties.style = buttonStyle.value;
                }
                break;
            case 'image':
                const imageSrc = document.getElementById('image-src');
                const imageAlt = document.getElementById('image-alt');
                const imageWidth = document.getElementById('image-width');
                const imageHeight = document.getElementById('image-height');
                if (imageSrc && imageAlt && imageWidth && imageHeight) {
                    properties.src = imageSrc.value;
                    properties.alt = imageAlt.value;
                    properties.width = imageWidth.value;
                    properties.height = imageHeight.value;
                }
                break;
            case 'link':
                const linkText = document.getElementById('link-text');
                const linkUrl = document.getElementById('link-url');
                const linkTarget = document.getElementById('link-target');
                if (linkText && linkUrl && linkTarget) {
                    properties.text = linkText.value;
                    properties.url = linkUrl.value;
                    properties.target = linkTarget.value;
                }
                break;
            case 'divider':
                const dividerStyle = document.getElementById('divider-style');
                const dividerColor = document.getElementById('divider-color');
                const dividerThickness = document.getElementById('divider-thickness');
                if (dividerStyle && dividerColor && dividerThickness) {
                    properties.style = dividerStyle.value;
                    properties.color = dividerColor.value;
                    properties.thickness = dividerThickness.value;
                }
                break;
        }
        
        // Update the component on the server
        fetch(`/cms/builder/${domainId}/component/${currentComponentId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                properties: properties,
                order: 0 // We'll keep the same order for now
            })
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
            showToast('Error updating component. Please try again.', 'error');
        });
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
        
        // Update the component content
        const contentElement = componentElement.querySelector('.component-content');
        if (contentElement) {
            switch(component.type) {
                case 'text':
                    contentElement.innerHTML = `<div class="text-content">${component.properties.content || ''}</div>`;
                    break;
                case 'button':
                    // Tambahkan penanganan style button
                    const buttonStyle = component.properties.style === 'secondary' ? 'bg-gray-500 hover:bg-gray-600' : 'bg-primary hover:bg-primary-dark';
                    contentElement.innerHTML = `<a href="${component.properties.url || '#'}" class="px-4 py-2 ${buttonStyle} text-white rounded-lg block text-center">${component.properties.text || 'Button'}</a>`;
                    break;
                case 'image':
                    // Tambahkan penanganan width dan height
                    const width = component.properties.width || '100%';
                    const height = component.properties.height || 'auto';
                    contentElement.innerHTML = `<img src="${component.properties.src || 'https://placehold.co/400x200'}" alt="${component.properties.alt || 'Image'}" style="width: ${width}; height: ${height};" class="rounded">`;
                    break;
                case 'link':
                    // Tambahkan penanganan target
                    const target = component.properties.target === '_blank' ? 'target="_blank" rel="noopener noreferrer"' : '';
                    contentElement.innerHTML = `<a href="${component.properties.url || '#'}" ${target} class="text-primary underline">${component.properties.text || 'Link'}</a>`;
                    break;
                case 'divider':
                    // Tambahkan penanganan style divider
                    const thickness = component.properties.thickness || '1px';
                    const color = component.properties.color || '#e5e7eb';
                    const style = component.properties.style || 'solid';
                    contentElement.innerHTML = `<hr style="border: ${thickness} ${style} ${color};">`;
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
            icon.innerHTML = '<path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>';
        } else if (type === 'error') {
            wrapper.classList.add('bg-red-50', 'text-red-800');
            icon.innerHTML = '<path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293 2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/>';
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
</script>
@endsection