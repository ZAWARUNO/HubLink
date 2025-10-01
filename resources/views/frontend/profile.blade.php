<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{ $domain->title ?? ucfirst($domain->slug) }} — HubLink</title>
	<script src="https://cdn.tailwindcss.com"></script>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
	<style>body{font-family:'Inter',sans-serif}</style>
	<script>
		tailwind.config = { theme: { extend: { colors: { primary: '#00c499', 'primary-dark':'#00a882' } } } }
	</script>
</head>
<body class="min-h-screen bg-gray-50">
	<div class="max-w-xl mx-auto px-4 py-12">
		<div class="bg-white rounded-3xl shadow-sm border p-8 text-center">
			<div class="w-24 h-24 rounded-full bg-primary/10 mx-auto mb-4 flex items-center justify-center">
				<svg class="w-10 h-10 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4a4 4 0 110 8 4 4 0 010-8zm0 8c-4 0-7 2-7 4v2h14v-2c0-2-3-4-7-4z"/></svg>
			</div>
			<h1 class="text-2xl font-bold text-gray-900">{{ $domain->title ?? '@'.$domain->slug }}</h1>
			<p class="text-gray-600 mt-2">{{ $domain->bio ?? 'Profil HubLink Anda' }}</p>
			<div class="mt-6 space-y-3">
				<a href="#" class="block w-full bg-primary hover:bg-primary-dark text-white py-3 rounded-xl font-semibold">Tombol Utama</a>
				<a href="#" class="block w-full border border-primary text-primary hover:bg-primary hover:text-white py-3 rounded-xl font-semibold">Tombol Kedua</a>
			</div>
		</div>
	</div>
</body>
</html>


