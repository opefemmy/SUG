@extends('admin.layout')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Add Academic Session</h1>
        <a href="{{ route('admin.academic.sessions.index') }}" class="text-blue-600 hover:underline">Back to List</a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-8 max-w-2xl">
        <form action="{{ route('admin.academic.sessions.store') }}" method="POST">
            @csrf
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Session Name</label>
                    <input type="text" name="session_name" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" placeholder="e.g. 2023/2024" required>
                </div>
                <div class="flex items-center space-x-2">
                    <input type="checkbox" name="is_current" value="1" class="w-4 h-4 text-blue-600">
                    <label class="text-sm font-medium text-gray-700">Set as Current Session</label>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-700 transition">Save Session</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
