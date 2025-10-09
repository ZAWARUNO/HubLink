/**
 * Image Resize Functionality for Page Builder
 * Handles drag-to-resize for image components
 */

// Add resize handles to image components
function addResizeHandles(componentElement) {
    const imageContainer = componentElement.querySelector('.resizable-image-container');
    if (!imageContainer) return;
    
    // Remove existing handles if any
    const existingHandles = imageContainer.querySelectorAll('.resize-handle');
    existingHandles.forEach(handle => handle.remove());
    
    // Add 8 resize handles (corners and sides)
    const positions = ['nw', 'n', 'ne', 'e', 'se', 's', 'sw', 'w'];
    positions.forEach(position => {
        const handle = document.createElement('div');
        handle.className = `resize-handle resize-handle-${position}`;
        handle.dataset.position = position;
        handle.addEventListener('mousedown', (e) => startResize(e, imageContainer, position));
        imageContainer.appendChild(handle);
    });
}

function startResize(e, container, position) {
    e.preventDefault();
    e.stopPropagation();
    
    const image = container.querySelector('img');
    if (!image) return;
    
    // Get initial dimensions
    const startX = e.clientX;
    const startY = e.clientY;
    const startWidth = container.offsetWidth;
    const startHeight = container.offsetHeight;
    
    // Get the component wrapper
    const componentWrapper = container.closest('.component-wrapper');
    if (componentWrapper) {
        componentWrapper.classList.add('resizing');
    }
    
    // Disable text selection during resize
    document.body.style.userSelect = 'none';
    document.body.style.cursor = getCursorForPosition(position);
    
    function doResize(e) {
        const deltaX = e.clientX - startX;
        const deltaY = e.clientY - startY;
        
        let newWidth = startWidth;
        let newHeight = startHeight;
        
        // Calculate new dimensions based on handle position
        switch(position) {
            case 'e': // East (right)
                newWidth = startWidth + deltaX;
                break;
            case 'w': // West (left)
                newWidth = startWidth - deltaX;
                break;
            case 's': // South (bottom)
                newHeight = startHeight + deltaY;
                break;
            case 'n': // North (top)
                newHeight = startHeight - deltaY;
                break;
            case 'se': // Southeast (bottom-right)
                newWidth = startWidth + deltaX;
                newHeight = startHeight + deltaY;
                break;
            case 'sw': // Southwest (bottom-left)
                newWidth = startWidth - deltaX;
                newHeight = startHeight + deltaY;
                break;
            case 'ne': // Northeast (top-right)
                newWidth = startWidth + deltaX;
                newHeight = startHeight - deltaY;
                break;
            case 'nw': // Northwest (top-left)
                newWidth = startWidth - deltaX;
                newHeight = startHeight - deltaY;
                break;
        }
        
        // Apply minimum dimensions
        newWidth = Math.max(50, newWidth);
        newHeight = Math.max(50, newHeight);
        
        // Apply new dimensions
        container.style.width = newWidth + 'px';
        container.style.height = newHeight + 'px';
    }
    
    function stopResize() {
        // Remove event listeners
        document.removeEventListener('mousemove', doResize);
        document.removeEventListener('mouseup', stopResize);
        
        // Re-enable text selection
        document.body.style.userSelect = '';
        document.body.style.cursor = '';
        
        // Remove resizing class
        if (componentWrapper) {
            componentWrapper.classList.remove('resizing');
        }
        
        // Save the new dimensions to the component properties
        const componentId = componentWrapper.dataset.id;
        const width = container.style.width;
        const height = container.style.height;
        
        // Update properties
        let properties = {};
        try {
            properties = JSON.parse(componentWrapper.dataset.properties || '{}');
        } catch (e) {
            console.error('Error parsing properties:', e);
            properties = {};
        }
        
        properties.width = width;
        properties.height = height;
        
        // Update the dataset
        componentWrapper.dataset.properties = JSON.stringify(properties);
        
        // Save to server if it's not a temporary component
        if (componentId && !componentId.startsWith('temp_')) {
            // Get domainId from the global scope (should be defined in the page)
            if (typeof domainId !== 'undefined') {
                fetch(`/cms/builder/${domainId}/component/${componentId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        properties: properties
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to save component dimensions');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Component dimensions saved successfully');
                    // Show success toast if available
                    if (typeof showToast === 'function') {
                        showToast('Image resized successfully', 'success');
                    }
                })
                .catch(error => {
                    console.error('Error saving dimensions:', error);
                    // Show error toast if available
                    if (typeof showToast === 'function') {
                        showToast('Error saving image dimensions', 'error');
                    }
                });
            }
        }
    }
    
    // Add event listeners
    document.addEventListener('mousemove', doResize);
    document.addEventListener('mouseup', stopResize);
}

function getCursorForPosition(position) {
    const cursors = {
        'n': 'n-resize',
        's': 's-resize',
        'e': 'e-resize',
        'w': 'w-resize',
        'ne': 'ne-resize',
        'nw': 'nw-resize',
        'se': 'se-resize',
        'sw': 'sw-resize'
    };
    return cursors[position] || 'default';
}

// Export functions for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { addResizeHandles, startResize };
}
