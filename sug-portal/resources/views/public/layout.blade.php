<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'SUG Portal') }} - Public Layout</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans text-gray-900">
    @yield('content')

    <footer class="bg-gray-900 text-white py-12 px-4">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-12">
            <div class="col-span-1 md:col-span-2">
                <a href="{{ route('home') }}" class="flex items-center gap-2 text-2xl font-bold text-white mb-6">
                    @php $footerLogo = \App\Services\SettingsService::get('brand_logo'); @endphp
                    @if($footerLogo)
                        <img src="{{ asset('storage/' . $footerLogo) }}" class="h-10 w-10 rounded" style="object-fit: contain;">
                    @else
                        <i class="fas fa-university"></i>
                    @endif
                    <span>{{ \App\Services\SettingsService::get('site_name', 'SUG Portal') }}</span>
                </a>
                <p class="text-gray-400 max-w-sm mb-6">{{ \App\Services\SettingsService::get('footer_description', 'The official Student Union Government platform dedicated to enhancing the student experience through digitalization, transparency, and accessibility.') }}</p>
                <div class="flex gap-4">
                    <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-blue-600 transition"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-blue-600 transition"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-blue-600 transition"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            <div>
                <h4 class="text-lg font-bold mb-6">{{ \App\Services\SettingsService::get('footer_links_title', 'Quick Links') }}</h4>
                <ul class="space-y-3 text-gray-400">
                    <li><a href="{{ route('about') }}" class="hover:text-white transition">{{ \App\Services\SettingsService::get('footer_about_link', 'About SUG') }}</a></li>
                    <li><a href="{{ route('news') }}" class="hover:text-white transition">{{ \App\Services\SettingsService::get('footer_news_link', 'Campus News') }}</a></li>
                    <li><a href="{{ route('events') }}" class="hover:text-white transition">{{ \App\Services\SettingsService::get('footer_events_link', 'Official Events') }}</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-white transition">{{ \App\Services\SettingsService::get('footer_contact_link', 'Contact Us') }}</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-lg font-bold mb-6">{{ \App\Services\SettingsService::get('footer_support_title', 'Student Support') }}</h4>
                <ul class="space-y-3 text-gray-400">
                    <li><a href="{{ route('login') }}" class="hover:text-white transition">{{ \App\Services\SettingsService::get('footer_login_link', 'Student Login') }}</a></li>
                    <li><a href="#" class="hover:text-white transition">{{ \App\Services\SettingsService::get('footer_verify_link', 'Verify Receipt') }}</a></li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto mt-12 pt-8 border-t border-gray-800 text-center text-gray-500 text-sm">
            &copy; {{ date('Y') }} {{ \App\Services\SettingsService::get('site_name', 'Student Union Government') }} Portal. {{ \App\Services\SettingsService::get('footer_copyright', 'All rights reserved.') }}
            <div class="mt-2 font-medium text-gray-400">
                Powered by the Directorate of ICT
            </div>
        </div>
    </footer>
</body>
</html>
