<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>HubLink CMS</title>
	<script src="https://cdn.tailwindcss.com"></script>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
	<style>body{font-family:'Inter',sans-serif}</style>
	<script>
		tailwind.config = { theme: { extend: { colors: { primary: '#00c499', 'primary-dark':'#00a882' } } } }
	</script>
</head>
<body class="bg-gray-50">
	<div class="min-h-screen flex">
		<!-- Sidebar -->
		<aside class="w-64 bg-white border-r border-gray-200 hidden md:flex md:flex-col fixed inset-y-0">
			<div class="h-16 flex items-center px-6 border-b"><span class="text-2xl font-bold text-primary">HubLink</span></div>
			<nav class="flex-1 p-4 space-y-1">
				<a href="{{ route('cms.home') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 text-gray-700">
					<svg class="w-5 h-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M4 10v10h6V14h4v6h6V10"/></svg>
					<span>Home</span>
				</a>
				<a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 text-gray-700">
					<svg class="w-5 h-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/></svg>
					<span>Hub</span>
				</a>
				<a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 text-gray-700">
					<svg class="w-5 h-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V7a2 2 0 00-2-2h-3l-2-2H9L7 5H5a2 2 0 00-2 2v6m17 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6"/></svg>
					<span>Produk</span>
				</a>
				<a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 text-gray-700">
					<svg class="w-5 h-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h10M9 21h6"/></svg>
					<span>Order</span>
				</a>
				<a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 text-gray-700">
					<svg class="w-5 h-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17v-4a4 4 0 00-4-4H5M13 7h6a2 2 0 012 2v10a2 2 0 01-2 2h-6a4 4 0 00-4-4v-4"/></svg>
					<span>Statistik</span>
				</a>
				<a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 text-gray-700">
					<svg class="w-5 h-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
					<span>Layout</span>
				</a>
				<form method="POST" action="{{ route('logout') }}">
					@csrf
					<button class="w-full flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 text-gray-700">
						<svg class="w-5 h-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 11-6 0V7a3 3 0 116 0v1"/></svg>
						<span>Logout</span>
					</button>
				</form>
			</nav>
		</aside>

		<!-- Main -->
		<div class="flex-1 md:ml-64 w-full">
			<header class="h-16 bg-white border-b flex items-center justify-between px-4 md:px-8 sticky top-0 z-10">
				<div class="flex items-center gap-3">
					<button class="md:hidden" id="btnOpenSidebar">
						<svg class="w-6 h-6 text-gray-700" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
					</button>
					<span class="font-semibold">Dashboard</span>
				</div>
				<div class="text-sm text-gray-500">{{ auth()->user()->email ?? '' }}</div>
			</header>
			<main class="p-4 md:p-8">
				@yield('content')
			</main>
		</div>
	</div>

	<script>
		// Optional: add simple sidebar toggle for mobile
	</script>
</body>
</html>


