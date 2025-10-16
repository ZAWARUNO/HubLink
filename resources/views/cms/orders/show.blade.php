@extends('cms.layouts.app')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-3">
        <a href="{{ route('cms.orders.index') }}" class="p-2 rounded-lg hover:bg-gray-100">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Detail Pesanan</h1>
            <p class="text-gray-600 text-sm mt-1">Order ID: {{ $order->order_id }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Status -->
            <div class="bg-white rounded-2xl border p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Status Pesanan</h3>
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Status Transaksi</p>
                        @if($order->transaction_status == 'settlement')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Pembayaran Berhasil
                            </span>
                        @elseif($order->transaction_status == 'pending')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                Menunggu Pembayaran
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                {{ ucfirst($order->transaction_status) }}
                            </span>
                        @endif
                    </div>
                    @if($order->paid_at)
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Dibayar pada</p>
                        <p class="text-sm font-medium text-gray-900">{{ $order->paid_at->format('d M Y, H:i') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Product Info -->
            <div class="bg-white rounded-2xl border p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Informasi Produk</h3>
                <div class="space-y-3">
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-600">Nama Produk</span>
                        <span class="font-medium text-gray-900">{{ $order->product_title }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-600">Harga</span>
                        <span class="font-medium text-gray-900">Rp {{ number_format($order->amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-600">Domain</span>
                        <span class="font-medium text-gray-900">{{ $order->domain->title ?? $order->domain->slug }}</span>
                    </div>
                    @if($order->transaction_status == 'settlement' && $order->digital_product_path)
                    <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center gap-2 text-green-800">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="font-medium">Produk Digital Tersedia</span>
                        </div>
                        <p class="text-sm text-green-700 mt-2">Customer dapat mengunduh produk melalui link yang dikirim ke email.</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Customer Info -->
            <div class="bg-white rounded-2xl border p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Informasi Customer</h3>
                <div class="space-y-3">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Nama</p>
                            <p class="font-medium text-gray-900">{{ $order->customer_name }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Email</p>
                            <p class="font-medium text-gray-900">{{ $order->customer_email }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Nomor HP</p>
                            <p class="font-medium text-gray-900">{{ $order->customer_phone }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Payment Info -->
            <div class="bg-white rounded-2xl border p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Informasi Pembayaran</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Metode Pembayaran</p>
                        <p class="font-medium text-gray-900 mt-1">{{ $order->payment_type ?? 'Midtrans' }}</p>
                    </div>
                    @if($order->snap_token)
                    <div>
                        <p class="text-sm text-gray-600">Snap Token</p>
                        <p class="text-xs font-mono text-gray-900 mt-1 break-all">{{ $order->snap_token }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-sm text-gray-600">Total Pembayaran</p>
                        <p class="text-2xl font-bold text-primary mt-1">Rp {{ number_format($order->amount, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="bg-white rounded-2xl border p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Timeline</h3>
                <div class="space-y-4">
                    <div class="flex gap-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Order Dibuat</p>
                            <p class="text-xs text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    @if($order->paid_at)
                    <div class="flex gap-3">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Pembayaran Diterima</p>
                            <p class="text-xs text-gray-500">{{ $order->paid_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    @endif
                    <div class="flex gap-3">
                        <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Terakhir Update</p>
                            <p class="text-xs text-gray-500">{{ $order->updated_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-2xl border p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Aksi</h3>
                <div class="space-y-2">
                    @if($order->transaction_status == 'settlement' && $order->digital_product_path)
                    <a href="{{ route('digital-product.page', $order->id) }}" target="_blank" class="block w-full px-4 py-2 bg-primary text-white text-center rounded-lg hover:bg-primary-dark transition-colors">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Halaman Download
                    </a>
                    @endif
                    <form action="{{ route('cms.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus order ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2 border border-red-300 text-red-600 rounded-lg hover:bg-red-50 transition-colors">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Hapus Order
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
