@extends('admin.layout')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Edit Page Content</h1>
        <a href="{{ route('cms.pages.index') }}" class="text-blue-600 hover:underline">Back to Pages</a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-8 max-w-4xl mx-auto">
        <form action="{{ route('cms.pages.update', $page->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Page Title</label>
                    <input type="text" name="title" value="{{ $page->title }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Page Content</label>
                    <textarea name="content" rows="15" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" required>{{ $page->content }}</textarea>
                    <p class="text-xs text-gray-500 mt-2">You can use basic HTML tags for formatting.</p>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-700 transition shadow-lg">
                        Update Page Content
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
