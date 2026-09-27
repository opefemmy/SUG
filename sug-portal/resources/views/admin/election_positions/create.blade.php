@extends('admin.layout')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Create Position for {{ $election->name }}</h1>
        <a href="{{ route('admin.elections.show', $election->id) }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors text-sm font-medium">
            Back to Election
        </a>
    </div>

    <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-8 max-w-2xl mx-auto">
        <form action="{{ route('admin.election-positions.store', $election->id) }}" method="POST">
            @csrf
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Position Name</label>
                    <input type="text" name="name" class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="e.g. President, Secretary" required>
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mt-10 flex justify-end">
                <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg transform hover:-translate-y-1">
                    Save Position
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
