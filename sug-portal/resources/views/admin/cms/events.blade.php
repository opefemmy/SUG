@extends('admin.layout')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Manage Campus Events</h1>
        <div class="flex gap-4">
            <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:underline">Back to Dashboard</a>
            <a href="{{ route('cms.events.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-blue-700 transition">Add New Event</a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-100">
                <tr class="text-gray-700">
                    <th class="p-4 font-bold border-b">Event Title</th>
                    <th class="p-4 font-bold border-b">Date</th>
                    <th class="p-4 font-bold border-b">Location</th>
                    <th class="p-4 font-bold border-b text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($events as $event)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-4">{{ $event->title }}</td>
                        <td class="p-4 text-sm text-gray-500">{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</td>
                        <td class="p-4">{{ $event->location }}</td>
                        <td class="p-4 text-right space-x-3">
                            <a href="{{ route('cms.events.edit', $event->id) }}" class="text-blue-600 hover:text-blue-800 font-medium">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-8 text-center text-gray-500">No events found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t">
            {{ $events->links() }}
        </div>
    </div>
</div>
@endsection
