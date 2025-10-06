<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>HubLink CMS</title>
	<script src="https://cdn.tailwindcss.com"></script>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
	<link href="{{ asset('css/builder.css') }}" rel="stylesheet">
	<style>body{font-family:'Inter',sans-serif}
		/* Mobile sidebar */
		.mobile-sidebar {
			transform: translateX(-100%);
			transition: transform 0.3s ease-in-out;
		}
		.mobile-sidebar.open {
			transform: translateX(0);
		}
		/* Responsive adjustments */
		@media (max-width: 768px) {
			.sidebar {
				position: fixed;
				z-index: 50;
				height: 100vh;
			}
			.main-content {
				margin-left: 0;
			}
			.header-title {
				font-size: 0.875rem;
			}
		}
		@media (min-width: 768px) {
			.mobile-sidebar {
				display: none;
			}
		}
	</style>
	<script>
		tailwind.config = { theme: { extend: { colors: { primary: '#00c499', 'primary-dark':'#00a882' } } } }
		
		function toggleSidebar() {
			const sidebar = document.getElementById('mobile-sidebar');
			sidebar.classList.toggle('open');
		}
		
		// Close sidebar when clicking outside
		document.addEventListener('click', function(event) {
			const sidebar = document.getElementById('mobile-sidebar');
			const openButton = document.getElementById('btnOpenSidebar');
			
			if (sidebar.classList.contains('open') && 
				!sidebar.contains(event.target) && 
				event.target !== openButton &&
				!openButton.contains(event.target)) {
				sidebar.classList.remove('open');
			}
		});
	</script>
</head>
<body class="bg-gray-50">
	<div class="min-h-screen flex">
		<!-- Mobile Sidebar Overlay -->
		<div class="mobile-sidebar sidebar w-64 bg-white border-r border-gray-200 md:hidden fixed inset-y-0 z-50" id="mobile-sidebar">
			<div class="h-16 flex items-center px-4 sm:px-6 border-b">
				<span class="text-xl sm:text-2xl font-bold text-primary">HubLink</span>
			</div>
			<nav class="flex-1 p-3 sm:p-4 space-y-1">
				<a href="{{ route('cms.home') }}" class="flex items-center gap-2 sm:gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 text-gray-700 text-sm sm:text-base">
					<svg class="w-4 h-4 sm:w-5 sm:h-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M4 10v10h6V14h4v6h6V10"/></svg>
					<span>Home</span>
				</a>
				<a href="{{ route('cms.builder.index') }}" class="flex items-center gap-2 sm:gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 text-gray-700 text-sm sm:text-base">
					<svg class="w-4 h-4 sm:w-5 sm:h-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
					<span>Builder</span>
				</a>
				<a href="{{ route('profile.edit') }}" class="flex items-center gap-2 sm:gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 text-gray-700 text-sm sm:text-base">
					<svg class="w-4 h-4 sm:w-5 sm:h-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
					<span>Profile</span>
				</a>
				<a href="#" class="flex items-center gap-2 sm:gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 text-gray-700 text-sm sm:text-base">
					<svg class="w-4 h-4 sm:w-5 sm:h-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/></svg>
					<span>Hub</span>
				</a>
				<a href="#" class="flex items-center gap-2 sm:gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 text-gray-700 text-sm sm:text-base">
					<svg class="w-4 h-4 sm:w-5 sm:h-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V7a2 2 0 00-2-2h-3l-2-2H9L7 5H5a2 2 0 00-2 2v6m17 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6"/></svg>
					<span>Produk</span>
				</a>
				<a href="#" class="flex items-center gap-2 sm:gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 text-gray-700 text-sm sm:text-base">
					<svg class="w-4 h-4 sm:w-5 sm:h-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17v-4a4 4 0 00-4-4H5M13 7h6a2 2 0 012 2v10a2 2 0 01-2 2h-6a4 4 0 00-4-4v-4"/></svg>
					<span>Order</span>
				</a>
				<a href="#" class="flex items-center gap-2 sm:gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 text-gray-700 text-sm sm:text-base">
					<svg class="w-4 h-4 sm:w-5 sm:h-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
					<span>Statistik</span>
				</a>
				<a href="#" class="flex items-center gap-2 sm:gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 text-gray-700 text-sm sm:text-base">
					<svg class="w-4 h-4 sm:w-5 sm:h-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
					<span>Layout</span>
				</a>
				<form method="POST" action="{{ route('logout') }}">
					@csrf
					<button class="w-full flex items-center gap-2 sm:gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 text-gray-700 text-left text-sm sm:text-base">
						<svg class="w-4 h-4 sm:w-5 sm:h-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 11-6 0V7a3 3 0 116 0v1"/></svg>
						<span>Logout</span>
					</button>
				</form>
			</nav>
		</div>

		<!-- Desktop Sidebar -->
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
				<a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 text-gray-700">
					<svg class="w-5 h-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
					<span>Profile</span>
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
					<svg class="w-5 h-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17v-4a4 4 0 00-4-4H5M13 7h6a2 2 0 012 2v10a2 2 0 01-2 2h-6a4 4 0 00-4-4v-4"/></svg>
					<span>Order</span>
				</a>
				<a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 text-gray-700">
					<svg class="w-5 h-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
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
		<div class="flex-1 md:ml-64 w-full main-content">
			<header class="h-14 sm:h-16 bg-white border-b flex items-center justify-between px-3 sm:px-4 md:px-8 sticky top-0 z-10">
				<div class="flex items-center gap-2 sm:gap-3">
					<button class="md:hidden" id="btnOpenSidebar" onclick="toggleSidebar()">
						<svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-700" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
					</button>
					<span class="font-semibold header-title text-sm sm:text-base">Dashboard</span>
				</div>
				<div class="flex items-center gap-2 sm:gap-3">
					<div class="w-6 h-6 sm:w-8 sm:h-8 rounded-full bg-gray-200 overflow-hidden border border-gray-300">
						@if(auth()->user()->profile_photo)
							<img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Profile Photo" class="w-full h-full object-cover">
						@else
							<div class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-full flex items-center justify-center">
								<svg class="w-3 h-3 sm:w-4 sm:h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
								</svg>
							</div>
						@endif
					</div>
					<div class="text-xs sm:text-sm text-gray-500 max-w-[100px] sm:max-w-none truncate">{{ auth()->user()->email ?? '' }}</div>
				</div>
			</header>
			<main class="p-3 sm:p-4 md:p-8">
				@yield('content')
			</main>
		</div>
	</div>
</body>
</html>