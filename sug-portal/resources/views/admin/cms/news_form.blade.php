@extends('admin.layout')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">{{ isset($news) ? 'Edit News' : 'Create News' }}</h1>
        <a href="{{ route('cms.news.index') }}" class="text-blue-600 hover:underline">Cancel</a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ isset($news) ? route('cms.news.update', $news->id) : route('cms.news.store') }}" method="POST">
            @csrf
            @if(isset($news))
                @method('PUT')
            @endif

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">News Title</label>
                    <input type="text" name="title" value="{{ old('title', $news->title ?? '') }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select name="category_id" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" required>
                        <option value="">Select Category</option>
                        @foreach(\App\Models\NewsCategory::all() as $cat)
                            <option value="{{ $cat->id }}" {{ (isset($news) && $news->category_id == $cat->id) ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Content</label>
                    <textarea name="content" rows="10" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" required>{{ old('content', $news->content ?? '') }}</textarea>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-700 transition">Save News Article</button>
            </div>
        </form>
    </div>
</div>
@endsection
