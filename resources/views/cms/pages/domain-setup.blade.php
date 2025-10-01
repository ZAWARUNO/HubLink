@extends('cms.layouts.app')

@section('content')
<div class="max-w-2xl">
	<h1 class="text-2xl font-bold text-gray-900 mb-6">Setup Domain</h1>
	<form method="POST" action="{{ route('cms.domain.store') }}" class="bg-white p-6 rounded-2xl border space-y-4">
		@csrf
		<div>
			<label class="block text-sm font-medium text-gray-700 mb-1">Nama Domain</label>
			<div class="flex">
				<span class="inline-flex items-center px-3 rounded-l-md border border-r-0 bg-gray-50 text-gray-500">hub.link/</span>
				<input name="slug" value="{{ old('slug') }}" class="flex-1 rounded-r-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary" placeholder="nama-domain" required>
			</div>
			@error('slug')
				<p class="text-red-600 text-sm mt-1">{{ $message }}</p>
			@enderror
		</div>
		<div>
			<label class="block text-sm font-medium text-gray-700 mb-1">Judul Halaman</label>
			<input name="title" value="{{ old('title') }}" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Nama brand / profil">
		</div>
		<div>
			<label class="block text-sm font-medium text-gray-700 mb-1">Bio Singkat</label>
			<textarea name="bio" rows="3" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Deskripsi singkat"></textarea>
		</div>
		<div class="flex justify-end">
			<button class="bg-primary hover:bg-primary-dark text-white px-5 py-2 rounded-lg">Simpan & Lanjut</button>
		</div>
	</form>
</div>
@endsection


