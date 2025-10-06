@extends('cms.layouts.app')

@section('content')
<div class="space-y-6">
	@if (session('status'))
		<div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">
			{{ session('status') }}
		</div>
	@endif

	<div class="grid md:grid-cols-3 gap-6">
		<div class="md:col-span-2 bg-white rounded-2xl border p-6">
			<div class="flex items-start gap-4 mb-4">
				<div class="w-16 h-16 rounded-full bg-gray-200 overflow-hidden border-2 border-gray-300">
					@if(auth()->user()->profile_photo)
						<img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Profile Photo" class="w-full h-full object-cover">
					@else
						<div class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-full flex items-center justify-center">
							<svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
							</svg>
						</div>
					@endif
				</div>
				<div>
					<h2 class="text-xl font-bold text-gray-900">Selamat datang, {{ $user->name }}</h2>
					<p class="text-gray-600">{{ $user->email }}</p>
				</div>
			</div>
			
			@if ($domain)
				<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
					<div>
						<p class="text-gray-600">Domain Anda</p>
						<div class="flex items-center gap-2">
							<a class="text-primary font-semibold" href="{{ url('/'.$domain->slug) }}" target="_blank">{{ url('/'.$domain->slug) }}</a>
							<button onclick="navigator.clipboard.writeText('{{ url('/'.$domain->slug) }}')" class="text-xs px-2 py-1 border rounded hover:bg-gray-50">Salin</button>
						</div>
					</div>
					<div class="flex gap-2">
						<a href="{{ route('cms.builder.index') }}" class="px-4 py-2 bg-primary hover:bg-primary-dark text-white rounded-lg">Builder</a>
						<a href="{{ route('cms.domain.setup') }}" class="px-4 py-2 border rounded-lg">Edit</a>
					</div>
				</div>
			@else
				<div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded">
					Anda belum membuat domain. <a href="{{ route('cms.domain.setup') }}" class="underline">Buat sekarang</a>.
				</div>
			@endif
		</div>
		<div class="bg-white rounded-2xl border p-6">
			<h3 class="font-semibold text-gray-900 mb-2">Pendapatan</h3>
			<p class="text-3xl font-bold text-gray-900">Rp0</p>
			<p class="text-gray-500 text-sm">Total diterima</p>
		</div>
	</div>

	<div class="grid md:grid-cols-4 gap-6">
		<div class="bg-white rounded-2xl border p-6">
			<p class="text-gray-500 text-sm">Total Views</p>
			<p class="text-2xl font-bold">0</p>
		</div>
		<div class="bg-white rounded-2xl border p-6">
			<p class="text-gray-500 text-sm">Total Klik</p>
			<p class="text-2xl font-bold">0</p>
		</div>
		<div class="bg-white rounded-2xl border p-6">
			<p class="text-gray-500 text-sm">Total Order</p>
			<p class="text-2xl font-bold">0</p>
		</div>
		<div class="bg-white rounded-2xl border p-6">
			<p class="text-gray-500 text-sm">Konversi</p>
			<p class="text-2xl font-bold">0%</p>
		</div>
	</div>
</div>
@endsection