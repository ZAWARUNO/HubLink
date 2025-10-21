// Mobile Builder JavaScript
let currentComponentId = null;
let currentComponentType = null;
let sortable = null;

// Bottom Sheet Functions
function openComponentsSheet() {
    document.getElementById('components-sheet').classList.add('active');
    document.getElementById('bottom-sheet-overlay').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function openPropertiesSheet() {
    document.getElementById('properties-sheet').classList.add('active');
    document.getElementById('bottom-sheet-overlay').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeBottomSheet() {
    document.getElementById('components-sheet').classList.remove('active');
    document.getElementById('properties-sheet').classList.remove('active');
    document.getElementById('bottom-sheet-overlay').classList.remove('active');
    document.body.style.overflow = '';
}

// Toast Notification
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toast-message');
    
    toastMessage.textContent = message;
    toast.classList.remove('translate-x-full');
    
    if (type === 'success') {
        toast.querySelector('.flex').classList.add('bg-green-50', 'text-green-800');
    } else {
        toast.querySelector('.flex').classList.add('bg-red-50', 'text-red-800');
    }
    
    setTimeout(() => {
        hideToast();
    }, 3000);
}

function hideToast() {
    const toast = document.getElementById('toast');
    toast.classList.add('translate-x-full');
}

// Add Component
async function addComponent(type) {
    closeBottomSheet();
    
    const properties = getDefaultProperties(type);
    const order = document.querySelectorAll('.component-wrapper').length;
    
    try {
        const response = await fetch(`/cms/builder/${domainId}/component`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                type: type,
                properties: properties,
                order: order
            })
        });
        
        if (!response.ok) throw new Error('Failed to add component');
        
        const component = await response.json();
        renderComponent(component);
        removeEmptyMessage();
        showToast('Komponen berhasil ditambahkan');
    } catch (error) {
        console.error('Error:', error);
        showToast('Gagal menambahkan komponen', 'error');
    }
}

// Edit Component
function editComponent(componentId) {
    const wrapper = document.querySelector(`[data-id="${componentId}"]`);
    if (!wrapper) return;
    
    currentComponentId = componentId;
    currentComponentType = wrapper.dataset.type;
    
    const properties = JSON.parse(wrapper.dataset.properties || '{}');
    loadPropertiesForm(currentComponentType, properties);
    openPropertiesSheet();
}

// Delete Component
async function deleteComponent(componentId) {
    if (!confirm('Hapus komponen ini?')) return;
    
    try {
        const response = await fetch(`/cms/builder/${domainId}/component/${componentId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        if (!response.ok) throw new Error('Failed to delete component');
        
        document.querySelector(`[data-id="${componentId}"]`).remove();
        
        if (document.querySelectorAll('.component-wrapper').length === 0) {
            showEmptyMessage();
        }
        
        showToast('Komponen berhasil dihapus');
    } catch (error) {
        console.error('Error:', error);
        showToast('Gagal menghapus komponen', 'error');
    }
}

// Preview Page
function previewPage() {
    window.open(`/${domainUser.slug}`, '_blank');
}

// Publish Page
async function publishPage() {
    try {
        const response = await fetch(`/cms/builder/${domainId}/publish`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        if (!response.ok) throw new Error('Failed to publish');
        
        showToast('Halaman berhasil dipublish!');
    } catch (error) {
        console.error('Error:', error);
        showToast('Gagal publish halaman', 'error');
    }
}

// Get Default Properties
function getDefaultProperties(type) {
    const defaults = {
        text: { 
            content: 'Teks baru', 
            alignment: 'left', 
            size: 'text-base',
            bold: false,
            italic: false,
            underline: false
        },
        button: { 
            text: 'Button', 
            url: '#', 
            backgroundColor: '#00c499', 
            textColor: '#ffffff',
            borderColor: '#00c499',
            borderWidth: '0',
            borderRadius: 'rounded-lg',
            padding: 'px-6 py-3',
            fontSize: 'text-base',
            fontWeight: 'font-medium'
        },
        image: { src: 'https://placehold.co/400x200', alt: 'Image', width: '100%', height: 'auto' },
        link: { text: 'Link', url: '#', textColor: '#00c499' },
        divider: { style: 'solid', color: '#e5e7eb', thickness: '1px' },
        profile: { showPhoto: true, showName: true, showUsername: true, alignment: 'left', layout: 'horizontal', photoSize: 'medium' },
        template: { isEmpty: true }
    };
    
    return defaults[type] || {};
}

// Render Component
function renderComponent(component) {
    const canvas = document.getElementById('canvas');
    const wrapper = document.createElement('div');
    wrapper.className = 'component-wrapper relative group border-2 border-transparent hover:border-dashed hover:border-primary rounded-lg p-3 transition-all';
    wrapper.dataset.id = component.id;
    wrapper.dataset.type = component.type;
    wrapper.dataset.properties = JSON.stringify(component.properties);
    
    wrapper.innerHTML = `
        <div class="component-actions">
            <button onclick="editComponent(${component.id})" class="action-btn text-blue-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </button>
            <button onclick="deleteComponent(${component.id})" class="action-btn text-red-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        </div>
        <div class="component-content pointer-events-none">
            ${getComponentHTML(component)}
        </div>
    `;
    
    canvas.appendChild(wrapper);
}

// Get Component HTML
function getComponentHTML(component) {
    const { type, properties } = component;
    
    switch(type) {
        case 'text':
            let textClasses = `text-${properties.alignment || 'left'} ${properties.size || 'text-base'}`;
            if (properties.bold) textClasses += ' font-bold';
            if (properties.italic) textClasses += ' italic';
            if (properties.underline) textClasses += ' underline';
            return `<div class="${textClasses}">${properties.content || 'Text'}</div>`;
        case 'button':
            let btnClasses = `block text-center ${properties.padding || 'px-6 py-3'} ${properties.borderRadius || 'rounded-lg'} ${properties.fontSize || 'text-base'} ${properties.fontWeight || 'font-medium'}`;
            let btnStyles = `background-color: ${properties.backgroundColor || '#00c499'}; color: ${properties.textColor || '#ffffff'};`;
            if (properties.borderWidth && properties.borderWidth !== '0') {
                btnStyles += ` border: ${properties.borderWidth}px solid ${properties.borderColor || '#00c499'};`;
            }
            return `<a href="${properties.url || '#'}" class="${btnClasses}" style="${btnStyles}">${properties.text || 'Button'}</a>`;
        case 'image':
            return `<img src="${properties.src || 'https://placehold.co/400x200'}" alt="${properties.alt || 'Image'}" class="w-full h-auto rounded">`;
        case 'link':
            return `<a href="${properties.url || '#'}" style="color: ${properties.textColor || '#00c499'};">${properties.text || 'Link'}</a>`;
        case 'divider':
            const style = properties.style || 'solid';
            const color = properties.color || '#e5e7eb';
            return `<hr style="border-style: ${style}; border-color: ${color};">`;
        case 'profile':
            return `<div class="flex items-center gap-4 p-4"><div class="w-16 h-16 rounded-full bg-primary/10"></div><div><h3 class="font-semibold">${domainUser.name}</h3><p class="text-sm text-gray-600">@${domainUser.slug}</p></div></div>`;
        case 'template':
            return `<div class="rounded-lg overflow-hidden shadow-lg bg-white"><div class="h-40 bg-gray-200"></div><div class="p-4"><div class="font-bold">Template</div></div></div>`;
        default:
            return '';
    }
}

// Load Properties Form
function loadPropertiesForm(type, properties) {
    const content = document.getElementById('properties-content');
    
    let html = '';
    
    switch(type) {
        case 'text':
            html = `
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Konten</label>
                        <textarea id="prop-content" class="w-full px-3 py-2 border border-gray-300 rounded-lg" rows="4">${properties.content || ''}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alignment</label>
                        <select id="prop-alignment" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            <option value="left" ${properties.alignment === 'left' ? 'selected' : ''}>Left</option>
                            <option value="center" ${properties.alignment === 'center' ? 'selected' : ''}>Center</option>
                            <option value="right" ${properties.alignment === 'right' ? 'selected' : ''}>Right</option>
                            <option value="justify" ${properties.alignment === 'justify' ? 'selected' : ''}>Justify</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ukuran Teks</label>
                        <select id="prop-size" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">Format Teks</label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" id="prop-bold" class="mr-2" ${properties.bold ? 'checked' : ''}>
                                <span class="text-sm">Bold</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" id="prop-italic" class="mr-2" ${properties.italic ? 'checked' : ''}>
                                <span class="text-sm">Italic</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" id="prop-underline" class="mr-2" ${properties.underline ? 'checked' : ''}>
                                <span class="text-sm">Underline</span>
                            </label>
                        </div>
                    </div>
                    <button onclick="saveProperties()" class="w-full bg-primary text-white py-3 rounded-lg font-medium">Simpan</button>
                </div>
            `;
            break;
        case 'button':
            html = `
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Teks Button</label>
                        <input type="text" id="prop-text" class="w-full px-3 py-2 border border-gray-300 rounded-lg" value="${properties.text || ''}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">URL</label>
                        <input type="text" id="prop-url" class="w-full px-3 py-2 border border-gray-300 rounded-lg" value="${properties.url || ''}" placeholder="https://example.com">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Warna Background</label>
                        <input type="color" id="prop-backgroundColor" class="w-full h-12 border border-gray-300 rounded-lg" value="${properties.backgroundColor || '#00c499'}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Warna Teks</label>
                        <input type="color" id="prop-textColor" class="w-full h-12 border border-gray-300 rounded-lg" value="${properties.textColor || '#ffffff'}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Warna Border</label>
                        <input type="color" id="prop-borderColor" class="w-full h-12 border border-gray-300 rounded-lg" value="${properties.borderColor || '#00c499'}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Lebar Border</label>
                        <select id="prop-borderWidth" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            <option value="0" ${properties.borderWidth === '0' ? 'selected' : ''}>None</option>
                            <option value="1" ${properties.borderWidth === '1' ? 'selected' : ''}>1px</option>
                            <option value="2" ${properties.borderWidth === '2' ? 'selected' : ''}>2px</option>
                            <option value="4" ${properties.borderWidth === '4' ? 'selected' : ''}>4px</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Border Radius</label>
                        <select id="prop-borderRadius" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            <option value="none" ${properties.borderRadius === 'none' ? 'selected' : ''}>None</option>
                            <option value="rounded" ${properties.borderRadius === 'rounded' ? 'selected' : ''}>Small</option>
                            <option value="rounded-md" ${properties.borderRadius === 'rounded-md' ? 'selected' : ''}>Medium</option>
                            <option value="rounded-lg" ${properties.borderRadius === 'rounded-lg' ? 'selected' : ''}>Large</option>
                            <option value="rounded-full" ${properties.borderRadius === 'rounded-full' ? 'selected' : ''}>Full</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Padding</label>
                        <select id="prop-padding" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            <option value="px-2 py-1" ${properties.padding === 'px-2 py-1' ? 'selected' : ''}>Small</option>
                            <option value="px-4 py-2" ${properties.padding === 'px-4 py-2' ? 'selected' : ''}>Medium</option>
                            <option value="px-6 py-3" ${properties.padding === 'px-6 py-3' ? 'selected' : ''}>Large</option>
                            <option value="px-8 py-4" ${properties.padding === 'px-8 py-4' ? 'selected' : ''}>Extra Large</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ukuran Font</label>
                        <select id="prop-fontSize" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            <option value="text-xs" ${properties.fontSize === 'text-xs' ? 'selected' : ''}>Extra Small</option>
                            <option value="text-sm" ${properties.fontSize === 'text-sm' ? 'selected' : ''}>Small</option>
                            <option value="text-base" ${properties.fontSize === 'text-base' ? 'selected' : ''}>Base</option>
                            <option value="text-lg" ${properties.fontSize === 'text-lg' ? 'selected' : ''}>Large</option>
                            <option value="text-xl" ${properties.fontSize === 'text-xl' ? 'selected' : ''}>Extra Large</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Font Weight</label>
                        <select id="prop-fontWeight" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            <option value="font-normal" ${properties.fontWeight === 'font-normal' ? 'selected' : ''}>Normal</option>
                            <option value="font-medium" ${properties.fontWeight === 'font-medium' ? 'selected' : ''}>Medium</option>
                            <option value="font-semibold" ${properties.fontWeight === 'font-semibold' ? 'selected' : ''}>Semibold</option>
                            <option value="font-bold" ${properties.fontWeight === 'font-bold' ? 'selected' : ''}>Bold</option>
                        </select>
                    </div>
                    <button onclick="saveProperties()" class="w-full bg-primary text-white py-3 rounded-lg font-medium">Simpan</button>
                </div>
            `;
            break;
        
        case 'image':
            html = `
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">URL Gambar</label>
                        <input type="text" id="prop-src" class="w-full px-3 py-2 border border-gray-300 rounded-lg" value="${properties.src || ''}" placeholder="https://example.com/image.jpg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Upload Gambar</label>
                        <input type="file" id="prop-image-file" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-lg" onchange="handleImageUpload(this)">
                        <p class="text-xs text-gray-500 mt-1">Atau upload gambar dari device</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alt Text</label>
                        <input type="text" id="prop-alt" class="w-full px-3 py-2 border border-gray-300 rounded-lg" value="${properties.alt || ''}" placeholder="Deskripsi gambar">
                    </div>
                    <button onclick="saveProperties()" class="w-full bg-primary text-white py-3 rounded-lg font-medium">Simpan</button>
                </div>
            `;
            break;
        
        case 'link':
            html = `
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Teks Link</label>
                        <input type="text" id="prop-text" class="w-full px-3 py-2 border border-gray-300 rounded-lg" value="${properties.text || ''}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">URL</label>
                        <input type="text" id="prop-url" class="w-full px-3 py-2 border border-gray-300 rounded-lg" value="${properties.url || ''}" placeholder="https://example.com">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Warna Teks</label>
                        <input type="color" id="prop-textColor" class="w-full h-12 border border-gray-300 rounded-lg" value="${properties.textColor || '#00c499'}">
                    </div>
                    <button onclick="saveProperties()" class="w-full bg-primary text-white py-3 rounded-lg font-medium">Simpan</button>
                </div>
            `;
            break;
        
        case 'divider':
            html = `
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Style</label>
                        <select id="prop-style" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            <option value="solid" ${properties.style === 'solid' ? 'selected' : ''}>Solid</option>
                            <option value="dashed" ${properties.style === 'dashed' ? 'selected' : ''}>Dashed</option>
                            <option value="dotted" ${properties.style === 'dotted' ? 'selected' : ''}>Dotted</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Warna</label>
                        <input type="color" id="prop-color" class="w-full h-12 border border-gray-300 rounded-lg" value="${properties.color || '#e5e7eb'}">
                    </div>
                    <button onclick="saveProperties()" class="w-full bg-primary text-white py-3 rounded-lg font-medium">Simpan</button>
                </div>
            `;
            break;
        
        case 'profile':
            html = `
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Layout</label>
                        <select id="prop-layout" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            <option value="horizontal" ${properties.layout === 'horizontal' ? 'selected' : ''}>Horizontal</option>
                            <option value="vertical" ${properties.layout === 'vertical' ? 'selected' : ''}>Vertical</option>
                            <option value="compact" ${properties.layout === 'compact' ? 'selected' : ''}>Compact</option>
                            <option value="card" ${properties.layout === 'card' ? 'selected' : ''}>Card</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alignment</label>
                        <select id="prop-alignment" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            <option value="left" ${properties.alignment === 'left' ? 'selected' : ''}>Left</option>
                            <option value="center" ${properties.alignment === 'center' ? 'selected' : ''}>Center</option>
                            <option value="right" ${properties.alignment === 'right' ? 'selected' : ''}>Right</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ukuran Foto</label>
                        <select id="prop-photoSize" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            <option value="small" ${properties.photoSize === 'small' ? 'selected' : ''}>Small</option>
                            <option value="medium" ${properties.photoSize === 'medium' ? 'selected' : ''}>Medium</option>
                            <option value="large" ${properties.photoSize === 'large' ? 'selected' : ''}>Large</option>
                            <option value="xlarge" ${properties.photoSize === 'xlarge' ? 'selected' : ''}>X-Large</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" id="prop-showPhoto" class="mr-2" ${properties.showPhoto !== false ? 'checked' : ''}>
                            <span class="text-sm text-gray-700">Tampilkan Foto</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" id="prop-showName" class="mr-2" ${properties.showName !== false ? 'checked' : ''}>
                            <span class="text-sm text-gray-700">Tampilkan Nama</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" id="prop-showUsername" class="mr-2" ${properties.showUsername !== false ? 'checked' : ''}>
                            <span class="text-sm text-gray-700">Tampilkan Username</span>
                        </label>
                    </div>
                    <button onclick="saveProperties()" class="w-full bg-primary text-white py-3 rounded-lg font-medium">Simpan</button>
                </div>
            `;
            break;
        
        case 'template':
            html = `
                <div class="space-y-4">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <p class="text-sm text-yellow-800">Template produk harus dikelola melalui halaman Products.</p>
                        <a href="/cms/products" class="text-sm text-primary font-medium hover:underline">Kelola Produk →</a>
                    </div>
                </div>
            `;
            break;
        
        default:
            html = '<p class="text-gray-500">Properti tidak tersedia</p>';
    }
    
    content.innerHTML = html;
}

// Handle Image Upload
async function handleImageUpload(input) {
    if (!input.files || !input.files[0]) return;
    
    const file = input.files[0];
    const formData = new FormData();
    formData.append('image', file);
    
    try {
        showToast('Mengupload gambar...');
        const response = await fetch(`/cms/builder/${domainId}/upload-image`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        });
        
        if (!response.ok) throw new Error('Failed to upload');
        
        const data = await response.json();
        document.getElementById('prop-src').value = window.location.origin + data.url;
        showToast('Gambar berhasil diupload');
    } catch (error) {
        console.error('Error:', error);
        showToast('Gagal upload gambar', 'error');
    }
}

// Save Properties
async function saveProperties() {
    const wrapper = document.querySelector(`[data-id="${currentComponentId}"]`);
    if (!wrapper) return;
    
    const properties = JSON.parse(wrapper.dataset.properties || '{}');
    
    // Update properties based on type
    switch(currentComponentType) {
        case 'text':
            properties.content = document.getElementById('prop-content').value;
            properties.alignment = document.getElementById('prop-alignment').value;
            properties.size = document.getElementById('prop-size').value;
            properties.bold = document.getElementById('prop-bold').checked;
            properties.italic = document.getElementById('prop-italic').checked;
            properties.underline = document.getElementById('prop-underline').checked;
            break;
        
        case 'button':
            properties.text = document.getElementById('prop-text').value;
            properties.url = document.getElementById('prop-url').value;
            properties.backgroundColor = document.getElementById('prop-backgroundColor').value;
            properties.textColor = document.getElementById('prop-textColor').value;
            properties.borderColor = document.getElementById('prop-borderColor').value;
            properties.borderWidth = document.getElementById('prop-borderWidth').value;
            properties.borderRadius = document.getElementById('prop-borderRadius').value;
            properties.padding = document.getElementById('prop-padding').value;
            properties.fontSize = document.getElementById('prop-fontSize').value;
            properties.fontWeight = document.getElementById('prop-fontWeight').value;
            break;
        
        case 'image':
            properties.src = document.getElementById('prop-src').value;
            properties.alt = document.getElementById('prop-alt').value;
            break;
        
        case 'link':
            properties.text = document.getElementById('prop-text').value;
            properties.url = document.getElementById('prop-url').value;
            properties.textColor = document.getElementById('prop-textColor').value;
            break;
        
        case 'divider':
            properties.style = document.getElementById('prop-style').value;
            properties.color = document.getElementById('prop-color').value;
            break;
        
        case 'profile':
            properties.layout = document.getElementById('prop-layout').value;
            properties.alignment = document.getElementById('prop-alignment').value;
            properties.photoSize = document.getElementById('prop-photoSize').value;
            properties.showPhoto = document.getElementById('prop-showPhoto').checked;
            properties.showName = document.getElementById('prop-showName').checked;
            properties.showUsername = document.getElementById('prop-showUsername').checked;
            break;
    }
    
    try {
        const response = await fetch(`/cms/builder/${domainId}/component/${currentComponentId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                properties: properties,
                order: Array.from(document.querySelectorAll('.component-wrapper')).indexOf(wrapper)
            })
        });
        
        if (!response.ok) throw new Error('Failed to update');
        
        wrapper.dataset.properties = JSON.stringify(properties);
        wrapper.querySelector('.component-content').innerHTML = getComponentHTML({ type: currentComponentType, properties });
        
        closeBottomSheet();
        showToast('Perubahan berhasil disimpan');
    } catch (error) {
        console.error('Error:', error);
        showToast('Gagal menyimpan perubahan', 'error');
    }
}

// Helper Functions
function removeEmptyMessage() {
    const msg = document.getElementById('empty-canvas-message');
    if (msg) msg.remove();
}

function showEmptyMessage() {
    const canvas = document.getElementById('canvas');
    canvas.innerHTML = `
        <div id="empty-canvas-message" class="text-center py-16 text-gray-500">
            <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            <h3 class="text-base font-medium text-gray-900 mb-1">Belum ada komponen</h3>
            <p class="text-sm text-gray-500">Tap tombol + untuk menambah</p>
        </div>
    `;
}

// Initialize Sortable
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('canvas');
    
    if (typeof Sortable !== 'undefined' && canvas) {
        sortable = new Sortable(canvas, {
            animation: 150,
            handle: '.component-wrapper',
            ghostClass: 'opacity-50',
            onEnd: async function(evt) {
                const components = Array.from(document.querySelectorAll('.component-wrapper')).map((el, index) => ({
                    id: parseInt(el.dataset.id),
                    order: index
                }));
                
                try {
                    await fetch(`/cms/builder/${domainId}/reorder`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ components })
                    });
                } catch (error) {
                    console.error('Error reordering:', error);
                }
            }
        });
    }
});
