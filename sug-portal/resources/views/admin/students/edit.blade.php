@extends('admin.layout')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Edit Student Profile</h1>
        <a href="{{ route('admin.students.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors text-sm font-medium">
            Back to List
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 max-w-4xl mx-auto">
        <form action="{{ route('admin.students.update', $student->id) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- User Account Section -->
                <div class="space-y-4">
                    <h3 class="text-lg font-bold text-gray-800 border-b pb-2">Account Details</h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $student->user->name) }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" required>
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $student->user->email) }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" required>
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Student Academic Section -->
                <div class="space-y-4">
                    <h3 class="text-lg font-bold text-gray-800 border-b pb-2">Academic Details</h3>
                    <div class="p-3 bg-indigo-50 border border-indigo-100 rounded-lg mb-4">
                        <p class="text-xs text-indigo-700 font-semibold">Promotion Info:</p>
                        <p class="text-xs text-indigo-600">Changing the Academic Session will automatically promote the student if they are at ND I or HND I.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">School</label>
                        <select name="school_id" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" required>
                            <option value="">Select School</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ old('school_id', $student->school_id) == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                            @endforeach
                        </select>
                        @error('school_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                        <select name="department_id" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" required>
                            <option value="">Select Department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department_id', $student->department_id) == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                            @endforeach
                        </select>
                        @error('department_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Programme</label>
                        <select name="programme_id" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" required>
                            <option value="">Select Programme</option>
                            @foreach($programmes as $programme)
                                <option value="{{ $programme->id }}" {{ old('programme_id', $student->programme_id) == $programme->id ? 'selected' : '' }}>{{ $programme->name }}</option>
                            @endforeach
                        </select>
                        @error('programme_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Current Level</label>
                            <select name="current_level_id" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                                <option value="">Auto-Promote</option>
                                @foreach($levels as $level)
                                    <option value="{{ $level->id }}" {{ old('current_level_id', $student->current_level_id) == $level->id ? 'selected' : '' }}>{{ $level->level_number }}</option>
                                @endforeach
                            </select>
                            <p class="text-[10px] text-gray-500 mt-1">Leave as 'Auto-Promote' to let the system handle it on session change.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Academic Session</label>
                            <select name="session_id" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" required>
                                <option value="">Select Session</option>
                                @foreach($sessions as $session)
                                    <option value="{{ $session->id }}" {{ old('session_id', $student->session_id) == $session->id ? 'selected' : '' }}>{{ $session->session_name }}</option>
                                @endforeach
                            </select>
                            @error('session_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-6">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-indigo-700 transition shadow-sm">
                    Update Student Profile
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
