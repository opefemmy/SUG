@extends('admin.layout')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Academic Sessions</h1>
        <a href="{{ route('admin.academic.sessions.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-blue-700 transition">
            + Add Session
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
                    <th class="p-4 font-bold border-b">Session Name</th>
                    <th class="p-4 font-bold border-b">Status</th>
                    <th class="p-4 font-bold border-b text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sessions as $session)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-4">{{ $session->session_name }}</td>
                        <td class="p-4">
                            @if($session->is_current)
                                <span class="px-2 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Current Session</span>
                            @else
                                <span class="px-2 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-700">Past/Future</span>
                            @endif
                        </td>
                        <td class="p-4 text-right space-x-2">
                            <a href="{{ route('admin.academic.sessions.edit', $session->id) }}" class="text-blue-600 hover:underline">Edit</a>
                            <form action="{{ route('admin.academic.sessions.destroy', $session->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Delete this session?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="p-8 text-center text-gray-500">No sessions found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t">
            {{ $sessions->links() }}
        </div>
    </div>
</div>
@endsection
