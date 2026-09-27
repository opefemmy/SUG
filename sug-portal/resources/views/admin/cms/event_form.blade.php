@extends('admin.layout')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">{{ isset($event) ? 'Edit Event' : 'Create Event' }}</h1>
        <a href="{{ route('cms.events.index') }}" class="text-blue-600 hover:underline">Cancel</a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ isset($event) ? route('cms.events.update', $event->id) : route('cms.events.store') }}" method="POST">
            @csrf
            @if(isset($event))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Event Title</label>
                    <input type="text" name="title" value="{{ old('title', $event->title ?? '') }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Event Date</label>
                    <input type="date" name="event_date" value="{{ old('event_date', isset($event) ? $event->event_date : '') }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                    <input type="text" name="location" value="{{ old('location', $event->location ?? '') }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" required>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="5" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" required>{{ old('description', $event->description ?? '') }}</textarea>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-700 transition">Save Event</button>
            </div>
        </form>
    </div>
</div>
@endsection
