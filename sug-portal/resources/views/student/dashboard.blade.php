@extends('student.layout')

@section('content')
<div class="space-y-8">
    <!-- Welcome Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Student Dashboard</h1>
            <p class="text-gray-500">Welcome back, {{ Auth::user()->name }}! Here's what's happening with your portal.</p>
        </div>
        <div class="flex items-center space-x-3 bg-white p-2 rounded-lg shadow-sm border border-gray-100">
            <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full uppercase">Active Account</span>
            <span class="text-gray-300">|</span>
            <span class="text-sm text-gray-600 font-medium">Session: 2026/2027</span>
        </div>
    </div>

    <!-- Fee Alert / Payment Card -->
    @if($isCleared)
        <div class="bg-green-600 rounded-2xl p-6 text-white shadow-lg flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-green-500 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold">Payment Cleared</h3>
                    <p class="text-green-100 text-sm">You have successfully paid all your fees for this session.</p>
                </div>
            </div>
            <div class="flex items-center space-x-6">
                <div class="text-right">
                    <p class="text-xs text-green-200 uppercase font-semibold">Outstanding Balance</p>
                    <p class="text-3xl font-black">₦0.00</p>
                </div>
                <a href="{{ route('student.receipts') }}" class="bg-white text-green-600 px-6 py-3 rounded-xl font-bold hover:bg-green-50 transition-all shadow-md transform hover:-translate-y-1">
                    My Receipts
                </a>
            </div>
        </div>
    @else
        <div class="bg-indigo-600 rounded-2xl p-6 text-white shadow-lg flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-indigo-500 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.5 0-3 .5-3 2s1.5 2 3 2 3-1 3-2-1.5-2-3-2z" />
                        <path stroke-linecap="round" stroke-linejoin, stroke-width="2" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h10a2 2 0 012 2v14a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold">Outstanding Balance</h3>
                    <p class="text-indigo-100 text-sm">Complete your payment to avoid registration delays.</p>
                </div>
            </div>
            <div class="flex items-center space-x-6">
                <div class="text-right">
                    <p class="text-xs text-indigo-200 uppercase font-semibold">Total Amount Due</p>
                    <p class="text-3xl font-black">₦{{ number_format($totalFees, 2) }}</p>
                </div>
                <a href="{{ route('student.fees.pay') }}" class="bg-white text-indigo-600 px-6 py-3 rounded-xl font-bold hover:bg-indigo-50 transition-all shadow-md transform hover:-translate-y-1">
                    Pay Now
                </a>
            </div>
        </div>
    @endif

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center space-x-4 hover:shadow-md transition-shadow">
            <div class="p-1 bg-indigo-100 rounded-2xl overflow-hidden">
                @if($student->biodata && $student->biodata->passport_path)
                    <img src="{{ Storage::url($student->biodata->passport_path) }}" class="h-16 w-16 rounded-xl object-cover" alt="Passport">
                @else
                    <div class="h-16 w-16 rounded-xl bg-indigo-200 flex items-center justify-center text-indigo-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                @endif
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">User Profile</p>
                <h3 class="text-xl font-bold text-gray-800 truncate max-w-[150px]">{{ Auth::user()->name }}</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center space-x-4 hover:shadow-md transition-shadow">
            <div class="p-4 bg-green-100 text-green-600 rounded-2xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Account Status</p>
                <h3 class="text-xl font-bold text-gray-800">Verified</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center space-x-4 hover:shadow-md transition-shadow">
            <div class="p-4 bg-blue-100 text-blue-600 rounded-2xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Current Year</p>
                <h3 class="text-xl font-bold text-gray-800">2026/2027</h3>
            </div>
        </div>
    </div>

    <!-- Quick Access Section -->
    <div class="space-y-4">
        <h2 class="text-xl font-bold text-gray-800 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
            Quick Access
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <a href="{{ route('student.biodata.index') }}" class="group p-6 bg-white rounded-2xl shadow-sm border border-gray-100 hover:border-indigo-300 hover:shadow-lg transition-all duration-200 flex flex-col items-center text-center">
                <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl group-hover:bg-indigo-600 group-hover:text-white transition-colors mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <span class="font-bold text-gray-700 group-hover:text-indigo-600 transition-colors">Update Biodata</span>
                <p class="text-xs text-gray-400 mt-1">Edit your personal details</p>
            </a>

            <a href="{{ route('student.profile') }}" class="group p-6 bg-white rounded-2xl shadow-sm border border-gray-100 hover:border-indigo-300 hover:shadow-lg transition-all duration-200 flex flex-col items-center text-center">
                <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl group-hover:bg-indigo-600 group-hover:text-white transition-colors mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <span class="font-bold text-gray-700 group-hover:text-indigo-600 transition-colors">My Profile</span>
                <p class="text-xs text-gray-400 mt-1">View academic record</p>
            </a>

            <a href="{{ route('student.fees') }}" class="group p-6 bg-white rounded-2xl shadow-sm border border-gray-100 hover:border-indigo-300 hover:shadow-lg transition-all duration-200 flex flex-col items-center text-center">
                <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl group-hover:bg-indigo-600 group-hover:text-white transition-colors mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.5 0-3 .5-3 2s1.5 2 3 2 3-1 3-2-1.5-2-3-2z" />
                    </svg>
                </div>
                <span class="font-bold text-gray-700 group-hover:text-indigo-600 transition-colors">Payment History</span>
                <p class="text-xs text-gray-400 mt-1">Check your fee status</p>
            </a>

            <a href="{{ route('student.receipts') }}" class="group p-6 bg-white rounded-2xl shadow-sm border border-gray-100 hover:border-indigo-300 hover:shadow-lg transition-all duration-200 flex flex-col items-center text-center">
                <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl group-hover:bg-indigo-600 group-hover:text-white transition-colors mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <span class="font-bold text-gray-700 group-hover:text-indigo-600 transition-colors">Download Receipts</span>
                <p class="text-xs text-gray-400 mt-1">Get your payment proofs</p>
            </a>
        </div>
    </div>

    @if(\App\Services\SettingsService::get('voting_enabled') && \App\Models\Election::where('status', 'Open')->exists())
    <div class="bg-orange-50 border-l-4 border-orange-500 p-6 rounded-r-2xl shadow-sm">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-orange-100 text-orange-600 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-orange-800">Live Election Ongoing!</h3>
                    <p class="text-orange-700 text-sm">Your vote counts. Participate in the current campus elections now.</p>
                </div>
            </div>
            <a href="{{ route('student.elections.index') }}" class="bg-orange-600 text-white px-6 py-2 rounded-xl font-bold hover:bg-orange-700 transition shadow-sm">
                Vote Now
            </a>
        </div>
    </div>
    @endif
</div>
@endsection
