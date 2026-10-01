@extends('admin.layout')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Student Details</h1>
        <a href="{{ route('admin.students.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors text-sm font-medium">
            Back to List
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="lg:col-span-1 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 text-center border-b border-gray-100">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($student->user->name) }}&background=random" class="h-24 w-24 rounded-full mx-auto border-4 border-indigo-50 mb-4" alt="User avatar">
                <h3 class="text-xl font-bold text-gray-900">{{ $student->user->name }}</h3>
                <p class="text-sm text-gray-500">{{ $student->user->email }}</p>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Status:</span>
                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $student->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ ucfirst($student->status) }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Matric No:</span>
                    <span class="text-sm font-mono font-bold text-gray-900">{{ $student->matric_no }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Admission Year:</span>
                    <span class="text-sm text-gray-900">{{ $student->admission_year }}</span>
                </div>
            </div>
            <div class="p-4 bg-gray-50 border-t border-gray-100">
                <a href="{{ route('admin.students.edit', $student->id) }}" class="block w-full text-center bg-indigo-600 text-white py-2 rounded-lg text-sm font-bold hover:bg-indigo-700 transition">
                    Edit Profile
                </a>
            </div>
        </div>

        <!-- Academic Details Card -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-800">Academic Information</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="p-2 bg-indigo-100 rounded-lg mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H7m2 0h5M9 7h10a2 2 0 012 2v11a2 2 0 01-2 2H7a2 2 0 01-2-2V9a2 2 0 012-2h10z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">School</p>
                            <p class="text-sm font-bold text-gray-900">{{ $student->school->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="p-2 bg-indigo-100 rounded-lg mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H7m2 0h5M9 7h10a2 2 0 012 2v11a2 2 0 01-2 2H7a2 2 0 01-2-2V9a2 2 0 012-2h10z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Department</p>
                            <p class="text-sm font-bold text-gray-900">{{ $student->department->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="p-2 bg-indigo-100 rounded-lg mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            </svg>
                            <p class="text-xs text-gray-500">Programme</p>
                            <p class="text-sm font-bold text-gray-900">{{ $student->programme->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="p-2 bg-indigo-100 rounded-lg mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            </svg>
                            <p class="text-xs text-gray-500">Current Level</p>
                            <p class="text-sm font-bold text-gray-900">{{ $student->level->level_number ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="p-2 bg-indigo-100 rounded-lg mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            </svg>
                            <p class="text-xs text-gray-500">Academic Session</p>
                            <p class="text-sm font-bold text-gray-900">{{ $student->session->session_name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
