@extends('admin.layout')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Admin Dashboard</h1>
        <p class="text-gray-600">Welcome back, {{ Auth::user()->name ?? 'Admin' }}. Here is an overview of the SUG portal.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-blue-100 text-blue-600 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-12 0v1z" />
                    </svg>
                </div>
                <span class="text-xs font-medium text-green-600 bg-green-100 px-2 py-1 rounded-full">Live</span>
            </div>
            <div class="text-2xl font-bold text-gray-800">{{ \App\Models\User::count() }} Users</div>
            <div class="text-sm text-gray-500">Total registered users in the system</div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-green-100 text-green-600 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.5 0-3 .5-3 2s1.5 2 3 2 3-1 3-2-1.5-2-3-2z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h10a2 2 0 012 2v14a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <span class="text-xs font-medium text-blue-600 bg-blue-100 px-2 py-1 rounded-full">Revenue</span>
            </div>
            <div class="text-2xl font-bold text-gray-800">₦{{ number_format(\App\Models\Payment::whereIn('status', ['success', 'successful', 'completed'])->sum('amount'), 2) }}</div>
            <div class="text-sm text-gray-500">Total revenue from successful payments</div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-yellow-100 text-yellow-600 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-xs font-medium text-yellow-600 bg-yellow-100 px-2 py-1 rounded-full">Transactions</span>
            </div>
            <div class="text-2xl font-bold text-gray-800">{{ \App\Models\Payment::whereIn('status', ['success', 'successful', 'completed'])->count() }}</div>
            <div class="text-sm text-gray-500">Total successful transactions</div>
        </div>
    </div>

    <h2 class="text-xl font-bold text-gray-800 mb-6">Quick Actions</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- CMS Management -->
        <a href="{{ route('settings.index') }}" class="group bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:border-blue-500 hover:shadow-md transition-all text-center">
            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
            <h3 class="font-bold text-gray-800">Portal CMS</h3>
            <p class="text-xs text-gray-500 mt-1">Edit homepage, news, and events</p>
        </a>

        <!-- Student Import -->
        <a href="{{ route('admin.students.import.index') }}" class="group bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:border-blue-500 hover:shadow-md transition-all text-center">
            <div class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-green-600 group-hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h3 class="font-bold text-gray-800">Bulk Import</h3>
            <p class="text-xs text-gray-500 mt-1">Upload students via CSV</p>
        </a>

        <!-- Payment Configuration -->
        <a href="{{ route('admin.payments.config.index') }}" class="group bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:border-blue-500 hover:shadow-md transition-all text-center">
            <div class="w-12 h-12 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-yellow-600 group-hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <h3 class="font-bold text-gray-800">Payment Setup</h3>
            <p class="text-xs text-gray-500 mt-1">Configure gateways and API keys</p>
        </a>

        <!-- User Management -->
        <a href="{{ route('admin.users.index') }}" class="group bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:border-blue-500 hover:shadow-md transition-all text-center">
            <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-12 0v1z" />
                </svg>
            </div>
            <h3 class="font-bold text-gray-800">Manage Users</h3>
            <p class="text-xs text-gray-500 mt-1">Roles, permissions, and accounts</p>
        </a>
    </div>
@endsection
