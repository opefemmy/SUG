@extends('admin.layout')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Add Academic Level</h1>
        <a href="{{ route('admin.academic.levels.index') }}" class="text-blue-600 hover:underline">Back to List</a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-8 max-w-2xl">
        <form action="{{ route('admin.academic.levels.store') }}" method="POST">
            @csrf
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Level Name/Number</label>
                    <input type="text" name="level_number" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" placeholder="e.g. 100 Level or Year 1" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Programme (Optional)</label>
                    <select name="programme_id" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="">All Programmes</option>
                        @foreach($programmes as $prog)
                            <option value="{{ $prog->id }}">{{ $prog->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-700 transition">Save Level</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
