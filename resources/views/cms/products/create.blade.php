@extends('cms.layouts.app')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('cms.products.index') }}" class="p-2 rounded-lg hover:bg-gray-100">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Tambah Produk Baru</h1>
    </div>

    <div class="bg-white rounded-2xl border p-4 sm:p-6">
        <form action="{{ route('cms.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div>
                <label for="domain_id" class="block text-sm font-medium text-gray-700 mb-1">Domain</label>
                <select name="domain_id" id="domain_id" class="w-full px-4 py-2 rounded-lg border-2 border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all">
                    <option value="">Pilih Domain</option>
                    @foreach($domains as $domain)
                        <option value="{{ $domain->id }}" {{ old('domain_id') == $domain->id ? 'selected' : '' }}>
                            {{ $domain->title ?? $domain->slug }}
                        </option>
                    @endforeach
                </select>
                @error('domain_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" class="w-full px-4 py-2 rounded-lg border-2 border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all" placeholder="Masukkan nama produk">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="description" id="description" rows="3" class="w-full px-4 py-2 rounded-lg border-2 border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all" placeholder="Masukkan deskripsi produk">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp)</label>
                <input type="number" name="price" id="price" value="{{ old('price', 0) }}" min="0" step="1000" class="w-full px-4 py-2 rounded-lg border-2 border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all" placeholder="0">
                @error('price')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Gambar Produk</label>
                
                <!-- Image Preview -->
                <div id="imagePreview" class="hidden mb-3">
                    <div class="relative inline-block">
                        <img id="imagePreviewImg" src="" alt="Preview" class="w-32 h-32 object-cover rounded-lg border-2 border-gray-300">
                        <button type="button" onclick="removeImage()" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <p id="imageFileName" class="text-sm text-gray-600 mt-2"></p>
                </div>
                
                <div id="imageUploadArea" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-primary transition-colors cursor-pointer">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600 justify-center">
                            <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-primary-dark">
                                <span>Upload gambar</span>
                                <input id="image" name="image" type="file" class="sr-only" accept="image/*" onchange="previewImage(event)">
                            </label>
                            <p class="pl-1">atau drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF sampai 2MB</p>
                    </div>
                </div>
                @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="digital_product" class="block text-sm font-medium text-gray-700 mb-1">File Produk Digital</label>
                
                <!-- File Selected Info -->
                <div id="fileInfo" class="hidden mb-3">
                    <div class="flex items-center gap-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <svg class="w-8 h-8 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <div class="flex-1 min-w-0">
                            <p id="fileName" class="text-sm font-medium text-gray-900 truncate"></p>
                            <p id="fileSize" class="text-xs text-gray-500"></p>
                        </div>
                        <button type="button" onclick="removeFile()" class="flex-shrink-0 text-red-500 hover:text-red-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <div id="fileUploadArea" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-primary transition-colors cursor-pointer">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600 justify-center">
                            <label for="digital_product" class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-primary-dark">
                                <span>Upload file</span>
                                <input id="digital_product" name="digital_product" type="file" class="sr-only" accept=".pdf,.zip,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.gif" onchange="previewFile(event)">
                            </label>
                            <p class="pl-1">atau drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PDF, ZIP, DOC, XLS sampai 10MB</p>
                    </div>
                </div>
                @error('digital_product')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex justify-end gap-3">
                <a href="{{ route('cms.products.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" id="submitBtn" class="px-4 py-2 bg-primary hover:bg-primary-dark text-white rounded-lg flex items-center gap-2">
                    <span id="submitText">Simpan Produk</span>
                    <svg id="loadingSpinner" class="hidden animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        // Validate file size (2MB = 2097152 bytes)
        if (file.size > 2097152) {
            alert('Ukuran gambar terlalu besar! Maksimal 2MB.');
            event.target.value = '';
            return;
        }
        
        // Validate file type
        if (!file.type.startsWith('image/')) {
            alert('File harus berupa gambar!');
            event.target.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreviewImg').src = e.target.result;
            document.getElementById('imageFileName').textContent = file.name + ' (' + formatFileSize(file.size) + ')';
            document.getElementById('imagePreview').classList.remove('hidden');
            document.getElementById('imageUploadArea').classList.add('hidden');
        }
        reader.readAsDataURL(file);
    }
}

function removeImage() {
    document.getElementById('image').value = '';
    document.getElementById('imagePreview').classList.add('hidden');
    document.getElementById('imageUploadArea').classList.remove('hidden');
}

function previewFile(event) {
    const file = event.target.files[0];
    if (file) {
        // Validate file size (10MB = 10485760 bytes)
        if (file.size > 10485760) {
            alert('Ukuran file terlalu besar! Maksimal 10MB.');
            event.target.value = '';
            return;
        }
        
        document.getElementById('fileName').textContent = file.name;
        document.getElementById('fileSize').textContent = formatFileSize(file.size);
        document.getElementById('fileInfo').classList.remove('hidden');
        document.getElementById('fileUploadArea').classList.add('hidden');
    }
}

function removeFile() {
    document.getElementById('digital_product').value = '';
    document.getElementById('fileInfo').classList.add('hidden');
    document.getElementById('fileUploadArea').classList.remove('hidden');
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

// Show loading state on form submit
document.querySelector('form').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const loadingSpinner = document.getElementById('loadingSpinner');
    
    submitBtn.disabled = true;
    submitText.textContent = 'Mengupload...';
    loadingSpinner.classList.remove('hidden');
});
</script>
@endsection