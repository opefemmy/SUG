@extends('admin.layout')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.pages.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center text-sm font-medium mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Pages
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Edit Page Content</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 max-w-4xl">
        <form action="{{ route('admin.pages.update', $page->slug) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Page Title</label>
                    <input type="text" name="title" value="{{ old('title', $page->title) }}" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                    <textarea name="content" rows="15" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none font-mono text-sm">{{ old('content', $page->content) }}</textarea>
                    <p class="text-xs text-gray-500 mt-2">You can use basic HTML tags for formatting.</p>
                </div>
                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.pages.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800 font-medium">Cancel</a>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-medium">Save Content</button>
                </div>
            </div>
        </form>
    </div>
@endsection
