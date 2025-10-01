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
			<h2 class="text-xl font-bold text-gray-900 mb-4">Selamat datang, {{ $user->name }}</h2>
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
						<button class="px-4 py-2 bg-primary hover:bg-primary-dark text-white rounded-lg">Bagikan</button>
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


