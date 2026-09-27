@extends('admin.layout')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Academic Levels</h1>
        <a href="{{ route('admin.academic.levels.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-blue-700 transition">
            + Add Level
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-100 border border-green-400 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-100">
                <tr class="text-gray-700">
                    <th class="p-4 font-bold border-b">Level Number/Name</th>
                    <th class="p-4 font-bold border-b">Programme</th>
                    <th class="p-4 font-bold border-b text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($levels as $level)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-4">{{ $level->level_number }}</td>
                        <td class="p-4">{{ $level->programme->name ?? 'All Programmes' }}</td>
                        <td class="p-4 text-right space-x-2">
                            <a href="{{ route('admin.academic.levels.edit', $level->id) }}" class="text-blue-600 hover:underline">Edit</a>
                            <form action="{{ route('admin.academic.levels.destroy', $level->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Delete this level?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="p-8 text-center text-gray-500">No levels found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t">
            {{ $levels->links() }}
        </div>
    </div>
</div>
@endsection
