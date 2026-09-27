@extends('admin.layout')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Edit Programme</h1>
        <a href="{{ route('admin.academic.programmes.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors text-sm font-medium">
            Cancel
        </a>
    </div>

    <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-8 max-w-2xl mx-auto">
        <form action="{{ route('admin.academic.programmes.update', $programme->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Parent Department</label>
                    <select name="department_id" class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none" required>
                        <option value="">Select Department</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ $programme->department_id == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }} ({{ $dept->school->name ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                    @error('department_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Programme Name</label>
                    <input type="text" name="name" value="{{ old('name', $programme->name) }}" class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none" required>
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Programme Code</label>
                    <input type="text" name="code" value="{{ old('code', $programme->code) }}" class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none" required>
                    @error('code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Duration (Years)</label>
                    <input type="number" name="duration_years" value="{{ old('duration_years', $programme->duration_years) }}" class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none" min="1" required>
                    @error('duration_years') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg">
                    Update Programme
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
