@extends('admin.layout')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Add New Department</h1>
        <a href="{{ route('admin.academic.departments.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors text-sm font-medium">
            Cancel
        </a>
    </div>

    <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-8 max-w-2xl mx-auto">
        <form action="{{ route('admin.academic.departments.store') }}" method="POST">
            @csrf
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Parent School</label>
                    <select name="school_id" class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none" required>
                        <option value="">Select School</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}">{{ $school->name }}</option>
                        @endforeach
                    </select>
                    @error('school_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Department Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none" required>
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Department Code</label>
                    <input type="text" name="code" value="{{ old('code') }}" class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none" required>
                    @error('code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg">
                    Create Department
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
