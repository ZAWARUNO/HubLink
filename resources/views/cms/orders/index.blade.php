@extends('cms.layouts.app')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Pesanan</h1>
            <p class="text-gray-600 mt-1">Kelola semua transaksi dan pesanan</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-6">
        <div class="bg-white rounded-2xl border p-4 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <p class="text-gray-500 text-xs sm:text-sm">Total Pesanan</p>
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ number_format($totalOrders) }}</p>
        </div>

        <div class="bg-white rounded-2xl border p-4 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <p class="text-gray-500 text-xs sm:text-sm">Berhasil</p>
                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ number_format($settlementOrders) }}</p>
        </div>

        <div class="bg-white rounded-2xl border p-4 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <p class="text-gray-500 text-xs sm:text-sm">Pending</p>
                <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ number_format($pendingOrders) }}</p>
        </div>

        <div class="bg-white rounded-2xl border p-4 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <p class="text-gray-500 text-xs sm:text-sm">Total Pendapatan</p>
                <div class="w-8 h-8 bg-primary/10 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xl sm:text-2xl font-bold text-gray-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl border p-4 sm:p-6">
        <form method="GET" action="{{ route('cms.orders.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Order ID, nama, email, produk..." 
                        class="w-full px-4 py-2 rounded-lg border-2 border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all">
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full px-4 py-2 rounded-lg border-2 border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all">
                        <option value="">Semua Status</option>
                        <option value="settlement" {{ request('status') == 'settlement' ? 'selected' : '' }}>Berhasil</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="expire" {{ request('status') == 'expire' ? 'selected' : '' }}>Expire</option>
                        <option value="cancel" {{ request('status') == 'cancel' ? 'selected' : '' }}>Cancel</option>
                    </select>
                </div>

                <!-- Domain Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Domain</label>
                    <select name="domain_id" class="w-full px-4 py-2 rounded-lg border-2 border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all">
                        <option value="">Semua Domain</option>
                        @foreach($domains as $domain)
                            <option value="{{ $domain->id }}" {{ request('domain_id') == $domain->id ? 'selected' : '' }}>
                                {{ $domain->title ?? $domain->slug }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Filter
                </button>
                <a href="{{ route('cms.orders.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-2xl border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Domain</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900">{{ $order->order_id }}</span>
                        </td>
                        <td class="px-4 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $order->product_title }}</div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $order->customer_name }}</div>
                            <div class="text-xs text-gray-500">{{ $order->customer_email }}</div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-600">{{ $order->domain->title ?? $order->domain->slug }}</span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="text-sm font-semibold text-gray-900">Rp {{ number_format($order->amount, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            @if($order->transaction_status == 'settlement')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Berhasil
                                </span>
                            @elseif($order->transaction_status == 'pending')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                    Pending
                                </span>
                            @elseif($order->transaction_status == 'expire')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    Expire
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ ucfirst($order->transaction_status) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $order->created_at->format('d M Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $order->created_at->format('H:i') }}</div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm">
                            <div class="flex gap-2">
                                <a href="{{ route('cms.orders.show', $order->id) }}" class="text-blue-600 hover:text-blue-800" title="Detail">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('cms.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus order ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-12 text-center">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <p class="text-gray-500 text-sm">Belum ada pesanan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
        <div class="px-4 py-3 border-t">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
