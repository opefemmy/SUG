@extends('admin.layout')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manage Pages</h1>
            <p class="text-gray-600">Edit rich content for the public site.</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-sm font-semibold text-gray-600">Page Slug</th>
                    <th class="px-6 py-3 text-sm font-semibold text-gray-600">Title</th>
                    <th class="px-6 py-3 text-right text-sm font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($pages as $page)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $page->slug }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $page->title ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.pages.edit', $page->slug) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-10 text-center text-gray-500 italic">No pages found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
