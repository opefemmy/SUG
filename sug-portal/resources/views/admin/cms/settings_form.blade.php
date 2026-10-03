@extends('admin.layout')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Site Settings</h1>
        <a href="{{ route('cms.settings.index') }}" class="text-blue-600 hover:underline">Back to CMS Hub</a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-100 border border-green-400 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <form action="{{ route('cms.settings.update') }}" method="POST">
            @csrf
            <div class="p-6 space-y-8">

                <!-- Home Page Section -->
                <div>
                    <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">Home Page Content</h3>

                    <div class="space-y-6">
                        <!-- Hero Section -->
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 space-y-4">
                            <h4 class="text-md font-semibold text-gray-600 mb-3">Hero Section</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Hero Title</label>
                                    <input type="text" name="settings[home_hero_title]" value="{{ $settings['home_hero_title'] ?? 'Empowering Students, Building the Future.' }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Hero Primary CTA</label>
                                    <input type="text" name="settings[home_hero_cta_primary]" value="{{ $settings['home_hero_cta_primary'] ?? 'Get Started' }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Hero Subtitle</label>
                                    <textarea name="settings[home_hero_subtitle]" rows="2" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">{{ $settings['home_hero_subtitle'] ?? 'The official digital hub for the Student Union Government.' }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Hero Secondary CTA</label>
                                    <input type="text" name="settings[home_hero_cta_secondary]" value="{{ $settings['home_hero_cta_secondary'] ?? 'Learn More' }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                            </div>
                        </div>

                        <!-- Services & Features -->
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 space-y-4">
                            <h4 class="text-md font-semibold text-gray-600 mb-3">Services & Features</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Services Title</label>
                                    <input type="text" name="settings[home_services_title]" value="{{ $settings['home_services_title'] ?? 'Our Student Services' }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Services Subtitle</label>
                                    <textarea name="settings[home_services_subtitle]" rows="2" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">{{ $settings['home_services_subtitle'] ?? 'Everything you need to navigate your university life.' }}</textarea>
                                </div>

                                <!-- Fee Feature -->
                                <div class="p-4 border rounded-lg bg-white space-y-3 shadow-sm">
                                    <p class="font-bold text-sm text-blue-600">Fee Management</p>
                                    <input type="text" name="settings[home_feature_fees_title]" value="{{ $settings['home_feature_fees_title'] ?? 'Fee Management' }}" placeholder="Title" class="w-full p-2 border rounded-lg text-sm">
                                    <textarea name="settings[home_feature_fees_desc]" rows="2" class="w-full p-2 border rounded-lg text-sm">{{ $settings['home_feature_fees_desc'] ?? 'Quickly pay your SUG dues...' }}</textarea>
                                    <input type="text" name="settings[home_feature_fees_cta]" value="{{ $settings['home_feature_fees_cta'] ?? 'Pay Now' }}" placeholder="CTA" class="w-full p-2 border rounded-lg text-sm">
                                </div>

                                <!-- Voting Feature -->
                                <div class="p-4 border rounded-lg bg-white space-y-3 shadow-sm">
                                    <p class="font-bold text-sm text-blue-600">Online Voting</p>
                                    <input type="text" name="settings[home_feature_voting_title]" value="{{ $settings['home_feature_voting_title'] ?? 'Online Voting' }}" placeholder="Title" class="w-full p-2 border rounded-lg text-sm">
                                    <textarea name="settings[home_feature_voting_desc]" rows="2" class="w-full p-2 border rounded-lg text-sm">{{ $settings['home_feature_voting_desc'] ?? 'Participate in SUG elections...' }}</textarea>
                                    <input type="text" name="settings[home_feature_voting_cta]" value="{{ $settings['home_feature_voting_cta'] ?? 'Vote Now' }}" placeholder="CTA" class="w-full p-2 border rounded-lg text-sm">
                                </div>

                                <!-- News Feature -->
                                <div class="p-4 border rounded-lg bg-white space-y-3 shadow-sm">
                                    <p class="font-bold text-sm text-blue-600">Campus News</p>
                                    <input type="text" name="settings[home_feature_news_title]" value="{{ $settings['home_feature_news_title'] ?? 'Campus News' }}" placeholder="Title" class="w-full p-2 border rounded-lg text-sm">
                                    <textarea name="settings[home_feature_news_desc]" rows="2" class="w-full p-2 border rounded-lg text-sm">{{ $settings['home_feature_news_desc'] ?? 'Stay informed with the latest...' }}</textarea>
                                    <input type="text" name="settings[home_feature_news_cta]" value="{{ $settings['home_feature_news_cta'] ?? 'View News' }}" placeholder="CTA" class="w-full p-2 border rounded-lg text-sm">
                                </div>
                            </div>
                        </div>

                        <!-- Updates Section -->
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 space-y-4">
                            <h4 class="text-md font-semibold text-gray-600 mb-3">Updates Section</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Updates Title</label>
                                    <input type="text" name="settings[home_updates_title]" value="{{ $settings['home_updates_title'] ?? 'Latest Campus Updates' }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Updates CTA</label>
                                    <input type="text" name="settings[home_updates_cta]" value="{{ $settings['home_updates_cta'] ?? 'View All News' }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Updates Subtitle</label>
                                    <textarea name="settings[home_updates_subtitle]" rows="2" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">{{ $settings['home_updates_subtitle'] ?? 'Keep up to date with what\'s happening on campus.' }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation & Footer -->
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 space-y-4">
                            <h4 class="text-md font-semibold text-gray-600 mb-3">Navigation & Footer</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nav: Home</label>
                                    <input type="text" name="settings[nav_home]" value="{{ $settings['nav_home'] ?? 'Home' }}" class="w-full p-2 border rounded-lg text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nav: About</label>
                                    <input type="text" name="settings[nav_about]" value="{{ $settings['nav_about'] ?? 'About' }}" class="w-full p-2 border rounded-lg text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nav: News</label>
                                    <input type="text" name="settings[nav_news]" value="{{ $settings['nav_news'] ?? 'News' }}" class="w-full p-2 border rounded-lg text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nav: Events</label>
                                    <input type="text" name="settings[nav_events]" value="{{ $settings['nav_events'] ?? 'Events' }}" class="w-full p-2 border rounded-lg text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nav: Contact</label>
                                    <input type="text" name="settings[nav_contact]" value="{{ $settings['nav_contact'] ?? 'Contact' }}" class="w-full p-2 border rounded-lg text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Back to Home Label</label>
                                    <input type="text" name="settings[nav_back_to_home]" value="{{ $settings['nav_back_to_home'] ?? 'Back to Home' }}" class="w-full p-2 border rounded-lg text-sm">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Copyright Text</label>
                                    <input type="text" name="settings[footer_copyright]" value="{{ $settings['footer_copyright'] ?? 'All rights reserved.' }}" class="w-full p-2 border rounded-lg text-sm">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Footer Attribution</label>
                                    <input type="text" name="settings[footer_attribution]" value="{{ $settings['footer_attribution'] ?? 'Powered by the Directorate of ICT' }}" class="w-full p-2 border rounded-lg text-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information Section -->
                    <div class="mt-8">
                        <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">Contact Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Contact Email</label>
                                <input type="email" name="settings[contact_email]" value="{{ $settings['contact_email'] ?? 'info@sugportal.com' }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Contact Phone</label>
                                <input type="text" name="settings[contact_phone]" value="{{ $settings['contact_phone'] ?? '+234 000 000 0000' }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-4 flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-700 transition">Save Settings</button>
            </div>
        </form>
    </div>
</div>
@endsection
