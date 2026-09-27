@extends('admin.layout')

@section('content')
    <div class="mb-6">
        <a href="{{ route('administration.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center text-sm font-medium mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Administrations
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Create New Administration Term</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 max-w-2xl">
        <form action="{{ route('administration.store') }}" method="POST">
            @csrf
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Term Name</label>
                    <input type="text" name="term_name" value="{{ old('term_name') }}" placeholder="e.g., 2026/2027 Administration" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none @error('term_name') border-red-500 @enderror" required>
                    @error('term_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                        <input type="date" name="start_date" value="{{ old('start_date') }}" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none @error('start_date') border-red-500 @enderror" required>
                        @error('start_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                        <input type="date" name="end_date" value="{{ old('end_date') }}" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none @error('end_date') border-red-500 @enderror" required>
                        @error('end_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="past" {{ old('status') === 'past' ? 'selected' : '' }}>Past Administration</option>
                        <option value="current" {{ old('status') === 'current' ? 'selected' : '' }}>Current Administration</option>
                    </select>
                </div>
            </div>
            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('administration.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800 font-medium">Cancel</a>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-medium">Create Term</button>
            </div>
        </form>
    </div>
@endsection
