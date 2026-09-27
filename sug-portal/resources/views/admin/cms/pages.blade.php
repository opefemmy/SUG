@extends('admin.layout')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Manage Static Pages</h1>
        <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:underline">Back to Dashboard</a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-100">
                <tr class="text-gray-700">
                    <th class="p-4 font-bold border-b">Page Title</th>
                    <th class="p-4 font-bold border-b">Slug (URL)</th>
                    <th class="p-4 font-bold border-b text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pages as $page)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-4 font-medium">{{ $page->title }}</td>
                        <td class="p-4 text-gray-500">{{ $page->slug }}</td>
                        <td class="p-4 text-right">
                            <a href="{{ route('cms.pages.edit', $page->id) }}" class="bg-blue-100 text-blue-700 px-3 py-1 rounded-lg text-sm font-bold hover:bg-blue-200 transition">Edit Content</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="p-8 text-center text-gray-500">No pages found. Please add them via database or seeder.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
