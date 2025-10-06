<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>HubLink CMS</title>
	<script src="https://cdn.tailwindcss.com"></script>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
	<link href="{{ asset('css/builder.css') }}?v={{ time() }}" rel="stylesheet">
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
				<a href="{{ route('cms.builder.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 text-gray-700">
					<svg class="w-5 h-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
					<span>Builder</span>
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
					<span>Statistik</span>
				</a>
			</nav>
			<div class="p-4 border-t border-gray-200">
				<a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 text-gray-700">
					<svg class="w-5 h-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
					<span>Logout</span>
				</a>
				<form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
			</div>
		</aside>

		<!-- Mobile sidebar toggle -->
		<div class="md:hidden fixed top-0 left-0 right-0 h-16 bg-white border-b border-gray-200 flex items-center px-4 z-10">
			<button type="button" class="text-gray-500 hover:text-gray-600" onclick="toggleSidebar()">
				<span class="sr-only">Open sidebar</span>
				<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
					<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
				</svg>
			</button>
			<div class="ml-4 text-xl font-bold text-primary">HubLink</div>
		</div>

		<!-- Main content -->
		<div class="flex-1 md:ml-64 pt-16 md:pt-0">
			<main class="p-4 md:p-6">
				@yield('content')
			</main>
		</div>
	</div>

	<!-- Mobile sidebar -->
	<div id="mobile-sidebar" class="fixed inset-0 z-20 hidden">
		<div class="absolute inset-0 bg-gray-600 bg-opacity-75" onclick="toggleSidebar()"></div>
		<div class="relative flex-1 flex flex-col max-w-xs w-full bg-white">
			<div class="h-16 flex items-center px-6 border-b">
				<span class="text-2xl font-bold text-primary">HubLink</span>
			</div>
			<nav class="flex-1 p-4 space-y-1">
				<a href="{{ route('cms.home') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 text-gray-700">
					<svg class="w-5 h-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M4 10v10h6V14h4v6h6V10"/></svg>
					<span>Home</span>
				</a>
				<a href="{{ route('cms.builder.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 text-gray-700">
					<svg class="w-5 h-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
					<span>Builder</span>
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
					<span>Statistik</span>
				</a>
			</nav>
			<div class="p-4 border-t border-gray-200">
				<a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 text-gray-700">
					<svg class="w-5 h-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
					<span>Logout</span>
				</a>
			</div>
		</div>
	</div>

	<script>
		function toggleSidebar() {
			const sidebar = document.getElementById('mobile-sidebar');
			if (sidebar) {
				sidebar.classList.toggle('hidden');
			}
		}
	</script>
</body>
</html>