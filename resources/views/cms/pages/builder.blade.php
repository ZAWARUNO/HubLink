@extends('cms.layouts.app')

@section('content')
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
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
    <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-hidden flex flex-col">
        <div class="p-4 border-b flex justify-between items-center">
            <h3 class="font-semibold text-gray-900">Page Preview</h3>
            <button onclick="closePreview()" class="text-gray-500 hover:text-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="flex-1 overflow-auto p-6 bg-gray-50">
            <div class="max-w-2xl mx-auto bg-white shadow-sm rounded-lg p-6">
                <div id="preview-content">
                    <!-- Preview content will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const domainId = {{ $domain->id }};
    let currentComponentId = null;
    let currentComponentType = null;
    
    document.addEventListener('DOMContentLoaded', function() {
        // Make components draggable
        document.querySelectorAll('.component-item').forEach(item => {
            item.addEventListener('dragstart', dragStart);
        });
        
        // Make canvas a drop zone
        const canvas = document.getElementById('canvas');
        canvas.addEventListener('dragover', dragOver);
        canvas.addEventListener('dragenter', dragEnter);
        canvas.addEventListener('dragleave', dragLeave);
        canvas.addEventListener('drop', drop);
    });
    
    function dragStart(e) {
        e.dataTransfer.setData('text/plain', e.target.dataset.type);
        e.dataTransfer.effectAllowed = 'copy';
    }
    
    function dragOver(e) {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'copy';
    }
    
    function dragEnter(e) {
        e.preventDefault();
        document.getElementById('canvas').classList.add('drag-over');
    }
    
    function dragLeave(e) {
        e.preventDefault();
        document.getElementById('canvas').classList.remove('drag-over');
    }
    
    function drop(e) {
        e.preventDefault();
        document.getElementById('canvas').classList.remove('drag-over');
        
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
                    <a href="#" class="px-4 py-2 bg-primary text-white rounded-lg block text-center">
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
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
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
        })
        .catch(error => {
            console.error('Error saving component:', error);
            alert('Error saving component. Please try again.');
        });
    }
    
    function getDefaultProperties(type) {
        switch(type) {
            case 'text':
                return { content: '<p>Edit this text content</p>' };
            case 'button':
                return { text: 'Button Text', url: '#' };
            case 'image':
                return { src: 'https://placehold.co/400x200', alt: 'Image' };
            case 'link':
                return { text: 'Link text', url: '#' };
            case 'divider':
                return {};
            default:
                return {};
        }
    }
    
    function editComponent(componentId) {
        const component = document.querySelector(`.component-wrapper[data-id="${componentId}"]`);
        if (!component) {
            console.error('Component not found:', componentId);
            alert('Component not found. Please try again.');
            return;
        }
        
        currentComponentId = componentId;
        currentComponentType = component.dataset.type;
        
        // Show properties panel
        document.getElementById('properties-panel').classList.remove('hidden');
        
        // Load properties based on component type
        loadProperties(component);
    }
    
    function loadProperties(component) {
        const propertiesContent = document.getElementById('properties-content');
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
                        <button onclick="saveComponentProperties()" class="w-full bg-primary hover:bg-primary-dark text-white py-2 rounded-lg">
                            Save Changes
                        </button>
                    </div>
                `;
                break;
            default:
                html = '<p>No properties to edit for this component type.</p>';
        }
        
        propertiesContent.innerHTML = html;
    }
    
    function saveComponentProperties() {
        if (!currentComponentId || !currentComponentType) return;
        
        let properties = {};
        
        switch(currentComponentType) {
            case 'text':
                properties.content = document.getElementById('text-content').value;
                break;
            case 'button':
                properties.text = document.getElementById('button-text').value;
                properties.url = document.getElementById('button-url').value;
                break;
            case 'image':
                properties.src = document.getElementById('image-src').value;
                properties.alt = document.getElementById('image-alt').value;
                break;
            case 'link':
                properties.text = document.getElementById('link-text').value;
                properties.url = document.getElementById('link-url').value;
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
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Update the component in the DOM
            updateComponentInDOM(data);
            closePropertiesPanel();
        })
        .catch(error => {
            console.error('Error updating component:', error);
            alert('Error updating component. Please try again.');
        });
    }
    
    function updateComponentInDOM(component) {
        const componentElement = document.querySelector(`.component-wrapper[data-id="${component.id}"]`);
        if (!componentElement) {
            console.error('Component element not found for ID:', component.id);
            return;
        }
        
        // Update the properties data attribute
        componentElement.dataset.properties = JSON.stringify(component.properties);
        
        // Update the component content
        const contentElement = componentElement.querySelector('.component-content');
        if (contentElement) {
            switch(component.type) {
                case 'text':
                    contentElement.innerHTML = `<div class="text-content">${component.properties.content || ''}</div>`;
                    break;
                case 'button':
                    contentElement.innerHTML = `<a href="${component.properties.url || '#'}" class="px-4 py-2 bg-primary text-white rounded-lg block text-center">${component.properties.text || 'Button'}</a>`;
                    break;
                case 'image':
                    contentElement.innerHTML = `<img src="${component.properties.src || 'https://placehold.co/400x200'}" alt="${component.properties.alt || 'Image'}" class="max-w-full h-auto rounded">`;
                    break;
                case 'link':
                    contentElement.innerHTML = `<a href="${component.properties.url || '#'}" class="text-primary underline">${component.properties.text || 'Link'}</a>`;
                    break;
                case 'divider':
                    contentElement.innerHTML = `<hr class="border-gray-300">`;
                    break;
                default:
                    contentElement.innerHTML = `<div>Unknown component type: ${component.type}</div>`;
            }
        }
    }
    
    function deleteComponent(componentId) {
        if (!confirm('Are you sure you want to delete this component?')) return;
        
        // Remove from server
        fetch(`/cms/builder/${domainId}/component/${componentId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
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
            }
        })
        .catch(error => {
            console.error('Error deleting component:', error);
            alert('Error deleting component. Please try again.');
        });
    }
    
    function closePropertiesPanel() {
        document.getElementById('properties-panel').classList.add('hidden');
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
            alert('Error generating preview. Please try again.');
        }
    }
    
    function closePreview() {
        document.getElementById('preview-modal').classList.add('hidden');
        document.getElementById('preview-modal').classList.remove('flex');
    }
    
    function publishPage() {
        if (!confirm('Are you sure you want to publish your changes?')) return;
        
        fetch(`/cms/builder/${domainId}/publish`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            alert('Page published successfully!');
        })
        .catch(error => {
            console.error('Error publishing page:', error);
            alert('Error publishing page. Please try again.');
        });
    }
    
    // Reorder components when dragged
    // This would be implemented with a proper drag-and-drop library in a real application
</script>
@endsection