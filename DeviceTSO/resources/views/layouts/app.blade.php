<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Device Checker Telkomsel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        @font-face {
            font-family: 'Telkomsel Batik Sans';
            src: url('/fonts/TelkomselBatikSans-Regular.woff2') format('woff2'),
                 url('/fonts/TelkomselBatikSans-Regular.woff') format('woff');
            font-weight: normal;
            font-style: normal;
        }
        @font-face {
            font-family: 'Telkomsel Batik Sans';
            src: url('/fonts/TelkomselBatikSans-Bold.woff2') format('woff2'),
                 url('/fonts/TelkomselBatikSans-Bold.woff') format('woff');
            font-weight: bold;
            font-style: normal;
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'telkomsel': ['Telkomsel Batik Sans', 'system-ui', 'sans-serif'],
                        'poppins': ['Poppins', 'system-ui', 'sans-serif']
                    },
                    colors: {
                        'telkomsel-red': '#FF0025',
                        'telkomsel-dark-red': '#B90024',
                        'telkomsel-yellow': '#FDA22B',
                        'telkomsel-blue': '#001A41',
                        'telkomsel-gray': '#DBDBDB'
                    }
                }
            }
        }
    </script>
    @stack('styles')
</head>
<body class="bg-gray-50 font-poppins">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div id="sidebar" class="bg-telkomsel-blue text-white transition-all duration-300 ease-in-out w-64 min-h-screen relative z-40">
           <!-- Logo Section -->
           <div class="flex items-center justify-between p-4 border-b border-white/20">
                <div class="flex items-center space-x-3">
                    <div class="bg-white rounded-lg w-10 h-10 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-telkomsel-red" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L2 7v10c0 5.55 3.84 9.74 9 10.92C16.16 26.74 20 22.55 20 17V7l-10-5z"/>
                        </svg>
                    </div>
                    <div id="logo-text" class="transition-opacity duration-300">
                        <h1 class="font-telkomsel font-bold text-lg">Device Checker</h1>
                        <p class="text-xs text-white/80">Telkomsel</p>
                    </div>
                </div>
                <button id="sidebar-toggle" class="p-2 rounded-lg hover:bg-white/10 transition-colors flex-shrink-0" title="Toggle sidebar">
                    <svg id="hamburger-open" class="w-5 h-5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg id="hamburger-close" class="w-5 h-5 transition-transform duration-300 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Collapsed Sidebar Toggle (Only visible when collapsed) -->
            <div id="collapsed-toggle" class="hidden p-4 border-b border-white/20 justify-center">
                <button id="collapsed-sidebar-toggle" class="p-2 rounded-lg hover:bg-white/10 transition-colors" title="Expand sidebar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            <!-- Navigation Menu -->
            <nav class="mt-4 px-4">
                <ul class="space-y-2">
                    <!-- Dashboard -->
                    <li>
                        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('dashboard') ? 'bg-white/20' : '' }}" title="Dashboard">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"/>
                            </svg>
                            <span class="nav-text transition-opacity duration-300">Dashboard</span>
                        </a>
                    </li>

                    <!-- Device Checking -->
                    <li>
                        <a href="{{ route('device-check.page') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('device-check.*') ? 'bg-white/20' : '' }}" title="Pengecekan Device">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="nav-text transition-opacity duration-300">Pengecekan Device</span>
                        </a>
                    </li>

                    <!-- Divider -->
                    <li class="nav-divider border-t border-white/20 my-4"></li>

                    <!-- Master Data Section -->
                    <li class="nav-section">
                        <div class="flex items-center space-x-3 px-3 py-2">
                            <svg class="w-4 h-4 text-white/60 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            <span class="nav-text text-sm text-white/60 font-medium transition-opacity duration-300">MASTER DATA</span>
                        </div>
                    </li>

                    <!-- Floors -->
                    <li>
                        <a href="{{ route('floors.index') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('floors.*') ? 'bg-white/20' : '' }}" title="Lantai">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span class="nav-text transition-opacity duration-300">Lantai</span>
                        </a>
                    </li>

                    <!-- Rooms -->
                    <li>
                        <a href="{{ route('rooms.index') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('rooms.*') ? 'bg-white/20' : '' }}" title="Ruangan">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10v11M20 10v11"/>
                            </svg>
                            <span class="nav-text transition-opacity duration-300">Ruangan</span>
                        </a>
                    </li>

                    <!-- Devices -->
                    <li>
                        <a href="{{ route('devices.index') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('devices.*') ? 'bg-white/20' : '' }}" title="Device">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span class="nav-text transition-opacity duration-300">Device</span>
                        </a>
                    </li>

                    <!-- Checklist Items -->
                    <li>
                        <a href="{{ route('checklist-items.index') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('checklist-items.*') ? 'bg-white/20' : '' }}" title="Checklist">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                            <span class="nav-text transition-opacity duration-300">Checklist</span>
                        </a>
                    </li>

                    @if(auth()->user()->isAdmin())
                    <!-- Divider -->
                    <li class="nav-divider border-t border-white/20 my-4"></li>

                    <!-- Admin Section -->
                    <li class="nav-section">
                        <div class="flex items-center space-x-3 px-3 py-2">
                            <svg class="w-4 h-4 text-white/60 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="nav-text text-sm text-white/60 font-medium transition-opacity duration-300">ADMIN</span>
                        </div>
                    </li>

                    <!-- Users Management -->
                    <li>
                        <a href="{{ route('users.index') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('users.*') ? 'bg-white/20' : '' }}" title="Kelola User">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                            </svg>
                            <span class="nav-text transition-opacity duration-300">Kelola User</span>
                        </a>
                    </li>
                    
                    <li class="nav-divider border-t border-white/20 my-4"></li>

                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center space-x-3 w-full text-left p-3 rounded-lg hover:bg-white/10 transition-colors" title="Logout">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                <span class="nav-text transition-opacity duration-300"> Logout </span>
                            </button>
                        </form>
                    </li>

                    @endif
                </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header -->
            <header class="bg-white shadow-sm border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <!-- Mobile Sidebar Toggle -->
                    <button id="mobile-sidebar-toggle" class="md:hidden p-2 rounded-lg text-gray-600 hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    
                    
                    <!-- Page Title -->
                    <div>
                        <h1 class="text-2xl font-telkomsel font-bold text-telkomsel-blue">@yield('page-title', 'Dashboard')</h1>
                        <p class="text-sm text-gray-600">@yield('page-subtitle', 'Sistem Pengecekan Device Telkomsel')</p>
                    </div>
                </div>

                <!-- User Profile -->
                <div class="flex items-center space-x-4">
                    <!-- User Menu -->
                    <div class="relative group">
                        <button class="flex items-center space-x-3 p-2 rounded-lg">
                            <div class="w-8 h-8 bg-telkomsel-red rounded-full flex items-center justify-center text-white font-bold text-sm">
                                {{ strtoupper(substr(auth()->user()->full_name ?? auth()->user()->username ?? 'U', 0, 1)) }}
                            </div>
                            <div class="text-left hidden sm:block">
                                <p class="text-sm font-medium text-gray-900">{{ auth()->user()->full_name ?? auth()->user()->username ?? 'User' }}</p>
                                <p class="text-xs text-gray-500 capitalize">{{ auth()->user()->role ?? 'user' }}</p>
                            </div>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 md:hidden hidden"></div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <script>
        // Sidebar toggle functionality
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const collapsedSidebarToggle = document.getElementById('collapsed-sidebar-toggle');
        const mobileSidebarToggle = document.getElementById('mobile-sidebar-toggle');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        const logoText = document.getElementById('logo-text');
        const navTexts = document.querySelectorAll('.nav-text');
        const navDividers = document.querySelectorAll('.nav-divider');
        const navSections = document.querySelectorAll('.nav-section');
        const collapsedToggle = document.getElementById('collapsed-toggle');

        let sidebarCollapsed = false;

        function toggleSidebar() {
            sidebarCollapsed = !sidebarCollapsed;
            
            if (sidebarCollapsed) {
                // Collapse sidebar
                sidebar.classList.remove('w-64');
                sidebar.classList.add('w-16');
                
                // Hide logo text and nav texts
                logoText.classList.add('opacity-0');
                navTexts.forEach(text => text.classList.add('opacity-0'));
                
                // Hide sections and dividers
                navDividers.forEach(divider => divider.classList.add('hidden'));
                navSections.forEach(section => section.classList.add('hidden'));
                
                // Show collapsed toggle after transition
                setTimeout(() => {
                    logoText.classList.add('hidden');
                    navTexts.forEach(text => text.classList.add('hidden'));
                    sidebarToggle.parentElement.classList.add('hidden');
                    collapsedToggle.classList.remove('hidden');
                    collapsedToggle.classList.add('flex');
                }, 150);
                
            } else {
                // Expand sidebar
                sidebar.classList.remove('w-16');
                sidebar.classList.add('w-64');
                
                // Hide collapsed toggle
                collapsedToggle.classList.remove('flex');
                collapsedToggle.classList.add('hidden');
                sidebarToggle.parentElement.classList.remove('hidden');
                
                // Show logo text and nav texts
                logoText.classList.remove('hidden');
                navTexts.forEach(text => text.classList.remove('hidden'));
                navDividers.forEach(divider => divider.classList.remove('hidden'));
                navSections.forEach(section => section.classList.remove('hidden'));
                
                setTimeout(() => {
                    logoText.classList.remove('opacity-0');
                    navTexts.forEach(text => text.classList.remove('opacity-0'));
                }, 50);
            }
        }

        function toggleMobileSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            sidebarOverlay.classList.toggle('hidden');
        }

        // Event listeners
        sidebarToggle.addEventListener('click', toggleSidebar);
        collapsedSidebarToggle.addEventListener('click', toggleSidebar);
        mobileSidebarToggle.addEventListener('click', toggleMobileSidebar);
        sidebarOverlay.addEventListener('click', toggleMobileSidebar);

        // Auto hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.animate-fade-in');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);

        // Mobile sidebar responsive
        function handleResize() {
            if (window.innerWidth >= 768) {
                sidebar.classList.remove('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                // Reset collapsed state on mobile
                if (sidebarCollapsed) {
                    sidebarCollapsed = false;
                    sidebar.classList.remove('w-16');
                    sidebar.classList.add('w-64');
                    collapsedToggle.classList.add('hidden');
                    sidebarToggle.parentElement.classList.remove('hidden');
                }
            }
        }

        window.addEventListener('resize', handleResize);
        handleResize(); // Initial call
    </script>

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }

        /* Collapsed sidebar styling */
        #sidebar.w-16 nav a {
            justify-content: center;
            padding: 0.75rem;
        }
        
        #sidebar.w-16 nav a svg {
            margin: 0 !important;
        }

        #sidebar.w-16 nav a:hover::after {
            content: attr(title);
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            white-space: nowrap;
            z-index: 50;
            margin-left: 0.5rem;
            pointer-events: none;
        }

        #sidebar.w-16 nav button:hover::after {
            content: attr(title);
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            white-space: nowrap;
            z-index: 50;
            margin-left: 0.5rem;
            pointer-events: none;
        }

        /* Mobile sidebar positioning */
        @media (max-width: 767px) {
            #sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                z-index: 40;
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
            }
            
            #sidebar:not(.-translate-x-full) {
                transform: translateX(0);
            }
        }

        /* Smooth transitions */
        #sidebar {
            transition: width 0.3s ease-in-out;
        }
        
        .nav-text, #logo-text {
            transition: opacity 0.3s ease-in-out;
        }
    </style>

    @stack('scripts')
</body>
</html>