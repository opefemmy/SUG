@extends('admin.layout')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manage Events</h1>
            <p class="text-gray-600">Create and organize upcoming campus events.</p>
        </div>
        <a href="{{ route('admin.events.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-700 transition">
            <i class="fas fa-plus mr-1"></i> Add Event
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr class="text-sm font-semibold text-gray-600">
                    <th class="px-6 py-3">Event</th>
                    <th class="px-6 py-3">Date</th>
                    <th class="px-6 py-3">Location</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($events as $event)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $event->title }}</div>
                            <div class="text-xs text-gray-500 truncate max-w-xs">{{ Str::limit($event->description, 50) }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $event->event_date->format('M d, Y H:i') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $event->location }}</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.events.edit', $event->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Edit</a>
                            <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure?')" class="text-red-600 hover:text-red-800 text-sm font-medium">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-gray-500 italic">No events found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
