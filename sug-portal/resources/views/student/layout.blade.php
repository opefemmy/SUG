<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - SUG Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        /* EMERGENCY FALLBACK STYLES - These work even if Tailwind fails to load */
        body {
            margin: 0;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: #f9fafb;
            color: #111827;
        }
        .app-container {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }
        .sidebar {
            background-color: #312e81; /* indigo-900 */
            color: white;
            display: flex;
            flex-direction: column;
            transition: width 0.3s;
            z-index: 20;
            flex-shrink: 0;
        }
        .sidebar-wide { width: 256px; }
        .sidebar-narrow { width: 80px; }
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            min-width: 0;
        }
        .top-header {
            background: white;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            border-bottom: 1px solid #e5e7eb;
            z-index: 10;
        }
        .content-area {
            flex: 1;
            overflow-y: auto;
            padding: 2rem;
            background-color: #f9fafb;
        }
        .nav-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: #c7d2fe;
            text-decoration: none;
            border-radius: 0.75rem;
            margin: 0 1rem 0.5rem 1rem;
            transition: all 0.2s;
        }
        .nav-item:hover { background-color: #3730a3; color: white; }
        .nav-item.active { background-color: #4338ca; color: white; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .nav-label { margin-left: 0.75rem; font-weight: 500; }

        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-900">
    <div class="app-container flex h-screen overflow-hidden" x-data="{ sidebarOpen: true }">
        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'sidebar sidebar-wide' : 'sidebar sidebar-narrow'" class="bg-indigo-900 text-white transition-all duration-300 flex flex-col z-20 shrink-0">
            <div class="p-6 flex items-center justify-between h-16">
                <div class="flex items-center overflow-hidden">
                    @php $logo = \App\Services\SettingsService::get('brand_logo'); @endphp
                    @if($logo)
                        <img src="{{ asset('storage/' . $logo) }}" class="h-8 w-8 rounded mr-2" style="object-fit: contain;">
                    @endif
                    <h1 x-show="sidebarOpen" x-cloak class="text-xl font-bold truncate">Student Portal</h1>
                </div>
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg hover:bg-indigo-800 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <nav class="flex-1 px-4 space-y-2 overflow-y-auto py-4">
                <a href="{{ route('student.dashboard') }}" class="nav-item flex items-center p-3 rounded-xl transition-all duration-200 {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 0m-2-0v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span x-show="sidebarOpen" x-cloak class="nav-label">Dashboard</span>
                </a>

                <div class="pt-6 pb-2">
                    <span x-show="sidebarOpen" x-cloak class="text-xs font-semibold text-indigo-300 uppercase px-3 tracking-wider">Support</span>
                </div>
                <a href="{{ route('student.support.index') }}" class="nav-item flex items-center p-3 rounded-xl transition-all duration-200 {{ request()->routeIs('student.support.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 0l-3.536-3.536m3.536 3.536l3.536 3.536m-3.536-3.536l-3.536 3.536m3.536-3.536l3.536-3.536" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h12m-12 4h12m-12 4h12" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span x-show="sidebarOpen" x-cloak class="nav-label">Log Complaint</span>
                </a>
                <div class="pt-6 pb-2">
                    <span x-show="sidebarOpen" x-cloak class="text-xs font-semibold text-indigo-300 uppercase px-3 tracking-wider">My Profile</span>
                </div>
                <a href="{{ route('student.biodata.index') }}" class="nav-item flex items-center p-3 rounded-xl transition-all duration-200 {{ request()->routeIs('student.biodata.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span x-show="sidebarOpen" x-cloak class="nav-label">Biodata</span>
                </a>
                <a href="{{ route('student.profile') }}" class="nav-item flex items-center p-3 rounded-xl transition-all duration-200 {{ request()->routeIs('student.profile') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    <span x-show="sidebarOpen" x-cloak class="nav-label">My Profile</span>
                </a>

                <div class="pt-6 pb-2">
                    <span x-show="sidebarOpen" x-cloak class="text-xs font-semibold text-indigo-300 uppercase px-3 tracking-wider">Finance</span>
                </div>
                <a href="{{ route('student.fees') }}" class="nav-item flex items-center p-3 rounded-xl transition-all duration-200 {{ request()->routeIs('student.fees') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.5 0-3 .5-3 2s1.5 2 3 2 3-1 3-2-1.5-2-3-2z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h10a2 2 0 012 2v14a2 2 0 01-2 2z" />
                    </svg>
                    <span x-show="sidebarOpen" x-cloak class="nav-label">Fees</span>
                </a>
                <a href="{{ route('student.receipts') }}" class="nav-item flex items-center p-3 rounded-xl transition-all duration-200 {{ request()->routeIs('student.receipts') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span x-show="sidebarOpen" x-cloak class="nav-label">My Receipts</span>
                </a>
            </nav>

            <div class="p-4 border-t border-indigo-800">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center w-full p-3 rounded-xl hover:bg-red-900 transition-colors text-red-300 hover:text-red-100 group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6-4v12" />
                        </svg>
                        <span x-show="sidebarOpen" x-cloak class="ml-3 font-medium">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content flex flex-col overflow-hidden">
            <header class="top-header bg-white border-b border-gray-200 z-10 h-16 flex items-center justify-between px-8 shrink-0">
                <div class="flex items-center">
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg hover:bg-gray-100 transition-colors text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-semibold text-gray-800 leading-tight">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500">Student Account</p>
                    </div>
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=4f46e5&color=fff" class="h-9 w-9 rounded-full border-2 border-indigo-100 shadow-sm" alt="User avatar">
                </div>
            </header>

<div class="content-area flex-1 overflow-y-auto bg-gray-50">
    <div class="max-w-7xl mx-auto p-6 lg:p-8">
        <!-- Global Flash Messages -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-r-xl shadow-sm flex items-center justify-between" role="alert">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-//-C15.35 12.34 13.5 11.5 12 11.5s-1.5.84-1.5 1.5m-1-1.5V14m1-1.5V14" clip-rule="evenodd" />
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L11 7.586l3.293-3.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
                <button @click="this.parentElement.remove()" class="text-green-500 hover:text-green-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 12.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-r-xl shadow-sm flex items-center justify-between" role="alert">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 001.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586l-1.293-1.293z" clip-rule="evenodd" />
                    </svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
                <button @click="this.parentElement.remove()" class="text-red-500 hover:text-red-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414-1.414L10 12.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        @endif

        @yield('content')
    </div>
</div>
        </main>
    </div>
</body>
</html>