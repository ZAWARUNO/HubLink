@extends('cms.layouts.app')

@section('content')
<div class="space-y-4 sm:space-y-6">
    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-3 py-2 sm:px-4 sm:py-3 rounded text-sm sm:text-base">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-3 py-2 sm:px-4 sm:py-3 rounded text-sm sm:text-base">
            {{ session('error') }}
        </div>
    @endif

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Daftar Produk</h1>
        <a href="{{ route('cms.products.create') }}" class="px-3 py-2 sm:px-4 sm:py-2 bg-primary hover:bg-primary-dark text-white rounded-lg text-sm sm:text-base flex items-center gap-2">
            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Produk
        </a>
    </div>

    @if($products->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            @foreach($products as $product)
                <div class="bg-white rounded-2xl border overflow-hidden">
                    <img class="w-full h-48 object-cover" src="{{ $product->properties['image'] ?? 'https://placehold.co/400x300' }}" alt="{{ $product->properties['title'] ?? 'Product Image' }}">
                    <div class="p-4 sm:p-6">
                        <h3 class="font-bold text-lg text-gray-900 mb-2">{{ $product->properties['title'] ?? 'Untitled Product' }}</h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $product->properties['description'] ?? 'No description' }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xl font-bold text-primary">Rp{{ number_format($product->properties['price'] ?? 0, 0, ',', '.') }}</span>
                            <div class="flex gap-2">
                                <a href="{{ route('cms.products.edit', $product->id) }}" class="px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm">
                                    Edit
                                </a>
                                <form action="{{ route('cms.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg text-sm">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-2xl border p-6 sm:p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6m17 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada produk</h3>
            <p class="mt-1 text-sm text-gray-500">Mulai dengan membuat produk digital pertama Anda.</p>
            <div class="mt-6">
                <a href="{{ route('cms.products.create') }}" class="px-4 py-2 bg-primary hover:bg-primary-dark text-white rounded-lg">
                    Tambah Produk
                </a>
            </div>
        </div>
    @endif
</div>
@endsection