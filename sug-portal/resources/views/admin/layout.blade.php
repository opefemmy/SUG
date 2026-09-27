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


            <nav class="flex-1 px-4 space-y-2 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center p-2 rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-slate-800' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 0m-2-0v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span x-show="sidebarOpen" class="ml-3">Dashboard</span>
                </a>

                <div class="pt-4 pb-2">
                    <span x-show="sidebarOpen" class="text-xs font-semibold text-slate-400 uppercase px-2">Academic Structure</span>
                </div>
                <div x-data="{ openAcademic: false }" class="space-y-1">
                    <button @click="openAcademic = !openAcademic" class="w-full flex items-center p-2 rounded-lg hover:bg-slate-800 transition-colors group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-//9 0H7m2 0h5M//9 7h10a2 2 0 012 2v11a2 2 0 01-2 2H7a2 2 0 01-2-2V9a2 2 0 012-2h10z" />
                        </svg>
                        <span x-show="sidebarOpen" class="ml-3 flex-1 text-left">Academic Setup</span>
                        <svg x-show="sidebarOpen" :class="openAcademic ? 'rotate-180' : ''" class="h-4 w-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="openAcademic" x-cloak class="pl-10 space-y-1">
                        <a href="{{ route('admin.academic.schools.index') }}" class="block p-2 text-sm rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('admin.academic.schools.*') ? 'bg-slate-800' : '' }}">Schools</a>
                        <a href="{{ route('admin.academic.departments.index') }}" class="block p-2 text-sm rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('admin.academic.departments.*') ? 'bg-slate-800' : '' }}">Departments</a>
                        <a href="{{ route('admin.academic.programmes.index') }}" class="block p-2 text-sm rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('admin.academic.programmes.*') ? 'bg-slate-800' : '' }}">Programmes</a>
                    </div>
                </div>
                <a href="{{ route('admin.academic.sessions.index') }}" class="flex items-center p-2 rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('admin.academic.sessions.*') ? 'bg-slate-800' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-//9 5h.01M12 20h.01" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span x-show="sidebarOpen" class="ml-3">Sessions</span>
                </a>
                <a href="{{ route('admin.academic.levels.index') }}" class="flex items-center p-2 rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('admin.academic.levels.*') ? 'bg-slate-800' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5a2 2 0 002 2h2a2 2 0 002-2" />
                    </svg>
                    <span x-show="sidebarOpen" class="ml-3">Levels</span>
                </a>

                <div class="pt-4 pb-2">
                    <span x-show="sidebarOpen" class="text-xs font-semibold text-slate-400 uppercase px-2">Election Management</span>
                </div>
                <a href="{{ route('admin.elections.index') }}" class="flex items-center p-2 rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('admin.elections.*') ? 'bg-slate-800' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span x-show="sidebarOpen" class="ml-3">Manage Elections</span>
                </a>
                <a href="{{ route('admin.candidates.index') }}" class="flex items-center p-2 rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('admin.candidates.*') ? 'bg-slate-800' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.999-1.8//m-10 0a3 3 0 01-3-3v-5a3 3 0 013-3h5.5//m-5 0v11a3 3 0 003 3h5" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span x-show="sidebarOpen" class="ml-3">Manage Candidates</span>
                </a>
                <a href="{{ route('student.elections.index') }}" class="flex items-center p-2 rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('student.elections.*') ? 'bg-slate-800' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span x-show="sidebarOpen" class="ml-3">Live Election Portal</span>
                </a>
                <div class="pt-4 pb-2">
                    <span x-show="sidebarOpen" class="text-xs font-semibold text-slate-400 uppercase px-2">User Management</span>
                </div>
                <a href="{{ route('admin.users.index') }}" class="flex items-center p-2 rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-slate-800' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-12 0v1z" />
                    </svg>
                    <span x-show="sidebarOpen" class="ml-3">Users</span>
                </a>
                <a href="{{ route('admin.roles.index') }}" class="flex items-center p-2 rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('admin.roles.*') ? 'bg-slate-800' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 1.016L3 6v2a2 2 0 002 2h14a2 2 0 002-2V6L17.618 2.016z" />
                    </svg>
                    <span x-show="sidebarOpen" class="ml-3">Roles</span>
                </a>

                <div class="pt-4 pb-2">
                    <span x-show="sidebarOpen" class="text-xs font-semibold text-slate-400 uppercase px-2">Student Services</span>
                </div>
                <a href="{{ route('admin.students.import.index') }}" class="flex items-center p-2 rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('admin.students.import.*') ? 'bg-slate-800' : '' }}">
                    <svg xmlns="http://www.w3org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span x-show="sidebarOpen" class="ml-3">Bulk Import</span>
                </a>

                <div class="pt-4 pb-2">
                    <span x-show="sidebarOpen" class="text-xs font-semibold text-slate-400 uppercase px-2">Financials</span>
                </div>
                <a href="{{ route('admin.fees.index') }}" class="flex items-center p-2 rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('admin.fees.*') ? 'bg-slate-800' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.5 0-3 .5-3 2s1.5 2 3 2 3-1 3-2-1.5-2-3-2z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h10a2 2 0 012 2v14a2 2 0 01-2 2z" />
                    </svg>
                    <span x-show="sidebarOpen" class="ml-3">Fee Configuration</span>
                </a>
                <a href="{{ route('admin.debtors.index') }}" class="flex items-center p-2 rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('admin.debtors.index') ? 'bg-slate-800' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span x-show="sidebarOpen" class="ml-3">Debtors List</span>
                </a>
                <a href="{{ route('admin.payments.config.index') }}" class="flex items-center p-2 rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('admin.payments.config.*') ? 'bg-slate-800' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" noqaattr="true" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span x-show="sidebarOpen" class="ml-3">Payment Setup</span>
                </a>
                <a href="{{ route('admin.payments.history') }}" class="flex items-center p-2 rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('admin.payments.history') ? 'bg-slate-800' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span x-show="sidebarOpen" class="ml-3">Payment History</span>
                </a>

                <div class="pt-4 pb-2">
                    <span x-show="sidebarOpen" class="text-xs font-semibold text-slate-400 uppercase px-2">Quick Setup</span>
                </div>
                <a href="{{ route('settings.index') }}" class="flex items-center p-2 rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('settings.*') ? 'bg-slate-800' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.44 1.547 2.19 3.73 2.19 6.227v1.06a6.75 6.75 0 01-13.5 0v-1.06c0-2.497.75-4.68 2.19-6.227a1.724 1.724 0 001.233-1.066zM//9 13a3 3 0 100 6 3 3 0 000-6z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15V3m-3 3l3-3 3 3" />
                    </svg>
                    <span x-show="sidebarOpen" class="ml-3">Site Branding</span>
                </a>
                <a href="{{ route('admin.academic.schools.index') }}" class="flex items-center p-2 rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('admin.academic.schools.*') ? 'bg-slate-800' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-//9 0H7m2 0h5M//9 7h10a2 2 0 012 2v11a2 2 0 01-2 2H7a2 2 0 01-2-2V9a2 2 0 012-2h10z" />
                    </svg>
                    <span x-show="sidebarOpen" class="ml-3">School Setup</span>
                </a>
                <a href="{{ route('admin.fees.index') }}" class="flex items-center p-2 rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('admin.fees.*') ? 'bg-slate-800' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.5 0-3 .5-3 2s1.5 2 3 2 3-1 3-2-1.5-2-3-2z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h10a2 2 0 012 2v14a2 2 0 01-2 2z" />
                    </svg>
                    <span x-show="sidebarOpen" class="ml-3">Fee Setup</span>
                </a>

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
