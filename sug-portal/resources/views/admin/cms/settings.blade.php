@extends('admin.layout')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Portal CMS Management</h1>
        <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:underline">Back to Dashboard</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <!-- Site Settings Card -->
        <a href="{{ route('cms.settings.form') }}" class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:border-blue-500 hover:shadow-md transition-all group">
            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mb-4 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.////-1.543 1.543 1.543 1.543 1.543 1.543 1.543 1.543" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V8m6 6v2m0-6V8" />
                </svg>
            </div>
            <h3 class="font-bold text-gray-800">Site Settings</h3>
            <p class="text-sm text-gray-500">Modify homepage text, slogans, and contact details</p>
        </a>

        <!-- Pages Management Card -->
        <a href="{{ route('cms.pages.index') }}" class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:border-blue-500 hover:shadow-md transition-all group">
            <div class="w-12 h-12 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mb-4 group-hover:bg-green-600 group-hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h3 class="font-bold text-gray-800">Static Pages</h3>
            <p class="text-sm text-gray-500">Edit "About", "Contact", and other informational pages</p>
        </a>

        <!-- News & Events Card -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <h3 class="font-bold text-gray-800 mb-4">Dynamic Content</h3>
            <div class="space-y-3">
                <a href="{{ route('cms.news.index') }}" class="flex items-center p-2 rounded hover:bg-gray-50 transition-colors text-sm text-gray-600 hover:text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5m14-15a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Manage News & Announcements
                </a>
                <a href="{{ route('cms.events.index') }}" class="flex items-center p-2 rounded hover:bg-gray-50 transition-colors text-sm text-gray-600 hover:text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 5h10M12 20h.01" />
                    </svg>
                    Manage Portal Events
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
