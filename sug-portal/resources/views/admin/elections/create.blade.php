@extends('admin.layout')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">{{ isset($election) ? 'Edit Election' : 'Create Election' }}</h1>
        <a href="{{ route('admin.elections.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors text-sm font-medium">
            Cancel
        </a>
    </div>

    <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-8 max-w-3xl mx-auto">
        <form action="{{ isset($election) ? route('admin.elections.update', $election->id) : route('admin.elections.store') }}" method="POST">
            @csrf
            @if(isset($election))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Election Name</label>
                    <input type="text" name="name" value="{{ old('name', $election->name ?? '') }}" class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none" required>
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="3" class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">{{ old('description', $election->description ?? '') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Academic Session</label>
                    <select name="session_id" class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none" required>
                        <option value="">Select Session</option>
                        @foreach($sessions as $session)
                            <option value="{{ $session->id }}" {{ (old('session_id', $election->session_id ?? '') == $session->id) ? 'selected' : '' }}>
                                {{ $session->session_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('session_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none" required>
                        <option value="Draft" {{ (old('status', $election->status ?? '') == 'Draft') ? 'selected' : '' }}>Draft</option>
                        <option value="Scheduled" {{ (old('status', $election->status ?? '') == 'Scheduled') ? 'selected' : '' }}>Scheduled</option>
                        <option value="Open" {{ (old('status', $election->status ?? '') == 'Open') ? 'selected' : '' }}>Open</option>
                        <option value="Closed" {{ (old('status', $election->status ?? '') == 'Closed') ? 'selected' : '' }}>Closed</option>
                        <option value="Published" {{ (old('status', $election->status ?? '') == 'Published') ? 'selected' : '' }}>Published</option>
                    </select>
                    @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                    <input type="datetime-local" name="start_date" value="{{ old('start_date', $election->start_date ?? '') }}" class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none" required>
                    @error('start_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                    <input type="datetime-local" name="end_date" value="{{ old('end_date', $election->end_date ?? '') }}" class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none" required>
                    @error('end_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg">
                    Save Election
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
