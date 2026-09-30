<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SUG Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: {{ \App\Services\SettingsService::get('site_background', '#f3f4f6') }} !important;
        }
        .primary-bg {
            background-color: {{ \App\Services\SettingsService::get('brand_primary_color', '#1e293b') }} !important;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: true }">
        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'w-64' : 'w-20'" class="primary-bg text-white transition-all duration-300 flex flex-col">
            <div class="p-6 flex items-center justify-between">
                <div class="flex items-center overflow-hidden">
                    @php $logo = \App\Services\SettingsService::get('brand_logo'); @endphp
                    @if($logo)
                        <img src="{{ asset('storage/' . $logo) }}" class="h-8 w-8 rounded mr-2" style="object-fit: contain;">
                    @endif
                    <h1 x-show="sidebarOpen" class="text-xl font-bold truncate">{{ \App\Services\SettingsService::get('site_name', 'SUG Admin') }}</h1>
                </div>
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded hover:bg-slate-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <nav class="flex-1 px-4 space-y-2 overflow-y-auto" x-data="{ activeMenu: null }">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center p-2 rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-slate-800' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 0m-2-0v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span x-show="sidebarOpen" class="ml-3">Dashboard</span>
                </a>

                <!-- Academic Structure Accordion -->
                <div class="space-y-1">
                    <button @click="activeMenu === 'academic' ? activeMenu = null : activeMenu = 'academic'"
                            :class="activeMenu === 'academic' ? 'bg-slate-800' : ''"
                            class="w-full flex items-center p-2 rounded-lg hover:bg-slate-800 transition-colors group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H7m2 0h5M9 7h10a2 2 0 012 2v11a2 2 0 01-2 2H7a2 2 0 01-2-2V9a2 2 0 012-2h10z" />
                        </svg>
                        <span x-show="sidebarOpen" class="ml-3 flex-1 text-left">Academic Setup</span>
                        <svg x-show="sidebarOpen" :class="activeMenu === 'academic' ? 'rotate-180' : ''" class="h-4 w-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="activeMenu === 'academic'" x-cloak class="pl-10 space-y-1 mt-1">
                        <a href="{{ route('admin.academic.schools.index') }}" class="block p-2 text-sm rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('admin.academic.schools.*') ? 'bg-slate-800' : '' }}">Schools</a>
                        <a href="{{ route('admin.academic.departments.index') }}" class="block p-2 text-sm rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('admin.academic.departments.*') ? 'bg-slate-800' : '' }}">Departments</a>
                        <a href="{{ route('admin.academic.programmes.index') }}" class="block p-2 text-sm rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('admin.academic.programmes.*') ? 'bg-slate-800' : '' }}">Programmes</a>
                        <a href="{{ route('admin.academic.sessions.index') }}" class="block p-2 text-sm rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('admin.academic.sessions.*') ? 'bg-slate-800' : '' }}">Sessions</a>
                        <a href="{{ route('admin.academic.levels.index') }}" class="block p-2 text-sm rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('admin.academic.levels.*') ? 'bg-slate-800' : '' }}">Levels</a>
                    </div>
                </div>

                <!-- Election Management Accordion -->
                <div class="space-y-1">
                    <button @click="activeMenu === 'election' ? activeMenu = null : activeMenu = 'election'"
                            :class="activeMenu === 'election' ? 'bg-slate-800' : ''"
                            class="w-full flex items-center p-2 rounded-lg hover:bg-slate-800 transition-colors group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span x-show="sidebarOpen" class="ml-3 flex-1 text-left">Elections</span>
                        <svg x-show="sidebarOpen" :class="activeMenu === 'election' ? 'rotate-180' : ''" class="h-4 w-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="activeMenu === 'election'" x-cloak class="pl-10 space-y-1 mt-1">
                        <a href="{{ route('admin.elections.index') }}" class="block p-2 text-sm rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('admin.elections.*') ? 'bg-slate-800' : '' }}">Manage Elections</a>
                        <a href="{{ route('admin.candidates.index') }}" class="block p-2 text-sm rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('admin.candidates.*') ? 'bg-slate-800' : '' }}">Manage Candidates</a>
                        <a href="{{ route('student.elections.index') }}" class="block p-2 text-sm rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('student.elections.*') ? 'bg-slate-800' : '' }}">Election Portal</a>
                    </div>
                </div>

                <!-- User Management Accordion -->
                <div class="space-y-1">
                    <button @click="activeMenu === 'users' ? activeMenu = null : activeMenu = 'users'"
                            :class="activeMenu === 'users' ? 'bg-slate-800' : ''"
                            class="w-full flex items-center p-2 rounded-lg hover:bg-slate-800 transition-colors group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-12 0v1z" />
                        </svg>
                        <span x-show="sidebarOpen" class="ml-3 flex-1 text-left">Users & Roles</span>
                        <svg x-show="sidebarOpen" :class="activeMenu === 'users' ? 'rotate-180' : ''" class="h-4 w-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="activeMenu === 'users'" x-cloak class="pl-10 space-y-1 mt-1">
                        <a href="{{ route('admin.users.index') }}" class="block p-2 text-sm rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-slate-800' : '' }}">Users</a>
                        <a href="{{ route('admin.roles.index') }}" class="block p-2 text-sm rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('admin.roles.*') ? 'bg-slate-800' : '' }}">Roles</a>
                    </div>
                </div>

                <!-- Student Services Accordion -->
                <div class="space-y-1">
                    <button @click="activeMenu === 'student' ? activeMenu = null : activeMenu = 'student'"
                            :class="activeMenu === 'student' ? 'bg-slate-800' : ''"
                            class="w-full flex items-center p-2 rounded-lg hover:bg-slate-800 transition-colors group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span x-show="sidebarOpen" class="ml-3 flex-1 text-left">Student Services</span>
                        <svg x-show="sidebarOpen" :class="activeMenu === 'student' ? 'rotate-180' : ''" class="h-4 w-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="activeMenu === 'student'" x-cloak class="pl-10 space-y-1 mt-1">
                        <a href="{{ route('admin.students.import.index') }}" class="block p-2 text-sm rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('admin.students.import.*') ? 'bg-slate-800' : '' }}">Bulk Import</a>
                    </div>
                </div>

                <!-- Financials Accordion -->
                <div class="space-y-1">
                    <button @click="activeMenu === 'finance' ? activeMenu = null : activeMenu = 'finance'"
                            :class="activeMenu === 'finance' ? 'bg-slate-800' : ''"
                            class="w-full flex items-center p-2 rounded-lg hover:bg-slate-800 transition-colors group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.5 0-3 .5-3 2s1.5 2 3 2 3-1 3-2-1.5-2-3-2z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h10a2 2 0 012 2v14a2 2 0 01-2 2z" />
                        </svg>
                        <span x-show="sidebarOpen" class="ml-3 flex-1 text-left">Financials</span>
                        <svg x-show="sidebarOpen" :class="activeMenu === 'finance' ? 'rotate-180' : ''" class="h-4 w-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="activeMenu === 'finance'" x-cloak class="pl-10 space-y-1 mt-1">
                        <a href="{{ route('admin.fees.index') }}" class="block p-2 text-sm rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('admin.fees.*') ? 'bg-slate-800' : '' }}">Fee Configuration</a>
                        <a href="{{ route('admin.debtors.index') }}" class="block p-2 text-sm rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('admin.debtors.index') ? 'bg-slate-800' : '' }}">Debtors List</a>
                        <a href="{{ route('admin.payments.config.index') }}" class="block p-2 text-sm rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('admin.payments.config.*') ? 'bg-slate-800' : '' }}">Payment Setup</a>
                        <a href="{{ route('admin.payments.history') }}" class="block p-2 text-sm rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('admin.payments.history') ? 'bg-slate-800' : '' }}">Payment History</a>
                    </div>
                </div>

                <!-- Site Setup Accordion -->
                <div class="space-y-1">
                    <button @click="activeMenu === 'setup' ? activeMenu = null : activeMenu = 'setup'"
                            :class="activeMenu === 'setup' ? 'bg-slate-800' : ''"
                            class="w-full flex items-center p-2 rounded-lg hover:bg-slate-800 transition-colors group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.44 1.547 2.19 3.73 2.19 6.227v1.06a6.75 6.75 0 01-13.5 0v-1.06c0-2.497.75-4.68 2.19-6.227a1.724 1.724 0 001.233-1.066zM9 13a3 3 0 100 6 3 3 0 000-6z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15V3m-3 3l3-3 3 3" />
                        </svg>
                        <span x-show="sidebarOpen" class="ml-3 flex-1 text-left">Site Branding</span>
                        <svg x-show="sidebarOpen" :class="activeMenu === 'setup' ? 'rotate-180' : ''" class="h-4 w-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="activeMenu === 'setup'" x-cloak class="pl-10 space-y-1 mt-1">
                        <a href="{{ route('settings.index') }}" class="block p-2 text-sm rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('settings.*') ? 'bg-slate-800' : '' }}">General Settings</a>
                    </div>
                </div>
            </nav>

            <div class="p-4 border-t border-slate-800">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center w-full p-2 rounded-lg hover:bg-red-900 transition-colors text-red-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6-4v12" />
                        </svg>
                        <span x-show="sidebarOpen" class="ml-3">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white shadow-sm z-10 h-16 flex items-center justify-between px-8">
                <div class="flex items-center">
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-md hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        </button>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">{{ Auth::user()->name }}</span>
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random" class="h-8 w-8 rounded-full border" alt="User avatar">
                </div>
            </header>

            <div class="flex-1 overflow-y-auto p-8">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>