@extends('student.layout')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">My Academic Profile</h1>
        <a href="{{ route('student.dashboard') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
            Back to Dashboard
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="lg:col-span-1 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="relative w-full h-full min-h-[400px]">
                @if($student->biodata && $student->biodata->passport_path)
                    <img src="{{ Storage::url($student->biodata->passport_path) }}" class="w-full h-full object-cover" alt="Student Passport">
                @else
                    <div class="w-full h-full bg-indigo-600 flex items-center justify-center">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random" class="h-32 w-32 rounded-full border-4 border-white shadow-md" alt="User avatar">
                    </div>
                @endif
            </div>
            <div class="px-6 pb-6 text-center">
                <h2 class="text-xl font-bold text-gray-900">{{ Auth::user()->name }}</h2>
                <p class="text-sm text-gray-500 mb-4">{{ $student->matric_no }}</p>
                <div class="flex justify-center space-x-2">
                    <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full uppercase">Active</span>
                </div>
            </div>
        </div>

        <!-- Details Card -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-6 pb-2 border-b border-gray-100">Academic Details</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-1">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Matriculation Number</p>
                    <p class="text-base font-medium text-gray-900">{{ $student->matric_no }}</p>
                </div>
                <div class="space-y-1">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Admission Year</p>
                    <p class="text-base font-medium text-gray-900">{{ $student->admission_year }}</p>
                </div>
                <div class="space-y-1">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">School</p>
                    <p class="text-base font-medium text-gray-900">
                        {{ $student->school->name ?? 'Not assigned' }}
                    </p>
                </div>
                <div class="space-y-1">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Department</p>
                    <p class="text-base font-medium text-gray-900">
                        {{ $student->department->name ?? 'Not assigned' }}
                    </p>
                </div>
                <div class="space-y-1">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Programme</p>
                    <p class="text-base font-medium text-gray-900">
                        {{ $student->programme->name ?? 'Not assigned' }}
                    </p>
                </div>
                <div class="space-y-1">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Current Level</p>
                    <p class="text-base font-medium text-gray-900">
                        Level {{ $student->level->level_number ?? 'N/A' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
