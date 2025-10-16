@extends('cms.layouts.app')

@section('content')
<div class="space-y-4 sm:space-y-6">
	@if (session('status'))
		<div class="bg-green-50 border border-green-200 text-green-800 px-3 py-2 sm:px-4 sm:py-3 rounded text-sm sm:text-base">
			{{ session('status') }}
		</div>
	@endif

	<div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
		<div class="md:col-span-2 bg-white rounded-2xl border p-4 sm:p-6">
			<div class="flex flex-col sm:flex-row items-start gap-3 sm:gap-4 mb-4">
				<div class="w-12 h-12 sm:w-16 sm:h-16 rounded-full bg-gray-200 overflow-hidden border-2 border-gray-300 flex-shrink-0">
					@if(auth()->user()->profile_photo)
						<img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Profile Photo" class="w-full h-full object-cover">
					@else
						<div class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-full flex items-center justify-center">
							<svg class="w-5 h-5 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
							</svg>
						</div>
					@endif
				</div>
				<div>
					<h2 class="text-lg sm:text-xl font-bold text-gray-900">Selamat datang, {{ $user->name }}</h2>
					<p class="text-gray-600 text-sm sm:text-base">{{ $user->email }}</p>
				</div>
			</div>
			
			@if ($domain)
				<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4 mt-4">
					<div>
						<p class="text-gray-600 text-sm">Domain Anda</p>
						<div class="flex flex-wrap items-center gap-1 sm:gap-2 mt-1">
							<a class="text-primary font-semibold text-sm sm:text-base break-all" href="{{ url('/'.$domain->slug) }}" target="_blank">{{ url('/'.$domain->slug) }}</a>
							<button onclick="navigator.clipboard.writeText('{{ url('/'.$domain->slug) }}')" class="text-xs sm:text-sm px-2 py-1 border rounded hover:bg-gray-50">Salin</button>
						</div>
					</div>
					<div class="flex gap-2">
						<a href="{{ route('cms.builder.index') }}" class="px-3 py-2 sm:px-4 sm:py-2 bg-primary hover:bg-primary-dark text-white rounded-lg text-sm">Builder</a>
						<a href="{{ route('cms.domain.setup') }}" class="px-3 py-2 sm:px-4 sm:py-2 border rounded-lg text-sm">Edit</a>
					</div>
				</div>
			@else
				<div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-3 py-2 sm:px-4 sm:py-3 rounded text-sm sm:text-base mt-4">
					Anda belum membuat domain. <a href="{{ route('cms.domain.setup') }}" class="underline">Buat sekarang</a>.
				</div>
			@endif
		</div>
		<div class="bg-gradient-to-br from-primary to-primary-dark rounded-2xl border p-4 sm:p-6 text-white">
			<div class="flex items-center justify-between mb-2">
				<h3 class="font-semibold text-sm sm:text-base">Pendapatan</h3>
				<svg class="w-8 h-8 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
				</svg>
			</div>
			<p class="text-2xl sm:text-3xl font-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
			<p class="text-white/80 text-xs sm:text-sm mt-1">Total diterima</p>
			<div class="mt-3 pt-3 border-t border-white/20">
				<p class="text-xs sm:text-sm">Bulan ini: <span class="font-semibold">Rp {{ number_format($revenueThisMonth, 0, ',', '.') }}</span></p>
			</div>
		</div>
	</div>

	<!-- Stats Cards -->
	<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-6">
		<div class="bg-white rounded-2xl border p-3 sm:p-6 hover:shadow-lg transition-shadow">
			<div class="flex items-center justify-between mb-2">
				<p class="text-gray-500 text-xs sm:text-sm">Pengunjung</p>
				<div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
					<svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
					</svg>
				</div>
			</div>
			<p class="text-xl sm:text-2xl font-bold text-gray-900">{{ number_format($totalVisitors) }}</p>
			<p class="text-xs text-gray-500 mt-1">Bulan ini: {{ number_format($visitorsThisMonth) }}</p>
		</div>
		
		<div class="bg-white rounded-2xl border p-3 sm:p-6 hover:shadow-lg transition-shadow">
			<div class="flex items-center justify-between mb-2">
				<p class="text-gray-500 text-xs sm:text-sm">Produk</p>
				<div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
					<svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
					</svg>
				</div>
			</div>
			<p class="text-xl sm:text-2xl font-bold text-gray-900">{{ number_format($totalProducts) }}</p>
			<p class="text-xs text-gray-500 mt-1">Total produk aktif</p>
		</div>
		
		<div class="bg-white rounded-2xl border p-3 sm:p-6 hover:shadow-lg transition-shadow">
			<div class="flex items-center justify-between mb-2">
				<p class="text-gray-500 text-xs sm:text-sm">Pesanan</p>
				<div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
					<svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m0 0h8.5M9.5 18h8.5"></path>
					</svg>
				</div>
			</div>
			<p class="text-xl sm:text-2xl font-bold text-gray-900">{{ number_format($totalOrders) }}</p>
			<p class="text-xs text-gray-500 mt-1">Bulan ini: {{ number_format($ordersThisMonth) }}</p>
		</div>
		
		<div class="bg-white rounded-2xl border p-3 sm:p-6 hover:shadow-lg transition-shadow">
			<div class="flex items-center justify-between mb-2">
				<p class="text-gray-500 text-xs sm:text-sm">Konversi</p>
				<div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
					<svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
					</svg>
				</div>
			</div>
			<p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $conversionRate }}%</p>
			<p class="text-xs text-gray-500 mt-1">Visitor → Order</p>
		</div>
	</div>

	<!-- Recent Orders -->
	@if($recentOrders->count() > 0)
	<div class="bg-white rounded-2xl border p-4 sm:p-6">
		<div class="flex items-center justify-between mb-4">
			<h3 class="font-semibold text-gray-900 text-sm sm:text-base">Transaksi Terbaru</h3>
			<a href="{{ route('cms.statistics.index') }}" class="text-primary text-xs sm:text-sm hover:underline">Lihat Semua</a>
		</div>
		<div class="space-y-3">
			@foreach($recentOrders as $order)
			<div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
				<div class="flex-1 min-w-0">
					<p class="font-medium text-gray-900 text-sm truncate">{{ $order->product_title }}</p>
					<p class="text-xs text-gray-500 mt-1">{{ $order->customer_name }} • {{ $order->created_at->diffForHumans() }}</p>
				</div>
				<div class="text-right ml-4">
					<p class="font-semibold text-gray-900 text-sm">Rp {{ number_format($order->amount, 0, ',', '.') }}</p>
					@if($order->transaction_status == 'settlement')
						<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 mt-1">
							✓ Berhasil
						</span>
					@elseif($order->transaction_status == 'pending')
						<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 mt-1">
							⏳ Pending
						</span>
					@else
						<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 mt-1">
							✗ {{ ucfirst($order->transaction_status) }}
						</span>
					@endif
				</div>
			</div>
			@endforeach
		</div>
	</div>
	@endif

	<!-- Quick Actions -->
	<div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl border border-blue-100 p-4 sm:p-6">
		<h3 class="font-semibold text-gray-900 mb-4 text-sm sm:text-base">Quick Actions</h3>
		<div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
			<a href="{{ route('cms.products.create') }}" class="flex flex-col items-center justify-center p-4 bg-white rounded-xl hover:shadow-md transition-shadow">
				<div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center mb-2">
					<svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
					</svg>
				</div>
				<span class="text-xs sm:text-sm font-medium text-gray-700">Tambah Produk</span>
			</a>
			<a href="{{ route('cms.builder.index') }}" class="flex flex-col items-center justify-center p-4 bg-white rounded-xl hover:shadow-md transition-shadow">
				<div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mb-2">
					<svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"></path>
					</svg>
				</div>
				<span class="text-xs sm:text-sm font-medium text-gray-700">Page Builder</span>
			</a>
			<a href="{{ route('cms.statistics.index') }}" class="flex flex-col items-center justify-center p-4 bg-white rounded-xl hover:shadow-md transition-shadow">
				<div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mb-2">
					<svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
					</svg>
				</div>
				<span class="text-xs sm:text-sm font-medium text-gray-700">Statistik</span>
			</a>
			<a href="{{ route('cms.domain.edit') }}" class="flex flex-col items-center justify-center p-4 bg-white rounded-xl hover:shadow-md transition-shadow">
				<div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mb-2">
					<svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
					</svg>
				</div>
				<span class="text-xs sm:text-sm font-medium text-gray-700">Pengaturan</span>
			</a>
		</div>
	</div>
</div>
@endsection