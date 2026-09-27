<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - {{ $settings['site_name'] ?? 'SUG Portal' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans text-gray-900">

    <!-- NAVIGATION BAR -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 text-2xl font-bold text-blue-700">
                        @php $logo = \App\Services\SettingsService::get('brand_logo'); @endphp
                        @if($logo)
                            <img src="{{ asset('storage/' . $logo) }}" class="h-10 w-10 rounded" style="object-fit: contain;">
                        @else
                            <i class="fas fa-university"></i>
                        @endif
                        <span>{{ $settings['site_name'] ?? 'SUG Portal' }}</span>
                    </a>
                    <div class="hidden md:ml-8 md:flex md:space-x-4">
                        <a href="{{ route('home') }}" class="text-gray-600 hover:text-blue-700 px-3 py-2 rounded-md text-sm font-medium">{{ $settings['nav_home'] ?? 'Home' }}</a>
                        <a href="{{ route('about') }}" class="text-gray-600 hover:text-blue-700 px-3 py-2 rounded-md text-sm font-medium">{{ $settings['nav_about'] ?? 'About' }}</a>
                        <a href="{{ route('news') }}" class="text-gray-600 hover:text-blue-700 px-3 py-2 rounded-md text-sm font-medium">{{ $settings['nav_news'] ?? 'News' }}</a>
                        <a href="{{ route('events') }}" class="text-gray-600 hover:text-blue-700 px-3 py-2 rounded-md text-sm font-medium">{{ $settings['nav_events'] ?? 'Events' }}</a>
                        <a href="{{ route('contact') }}" class="text-blue-700 bg-blue-50 px-3 py-2 rounded-md text-sm font-medium">{{ $settings['nav_contact'] ?? 'Contact' }}</a>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ auth()->user()->hasRole('admin') ? route('admin.dashboard') : route('student.dashboard') }}"
                           class="bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-800 transition">
                            {{ $settings['nav_dashboard'] ?? 'Dashboard' }}
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-red-600 text-sm font-medium">{{ $settings['nav_logout'] ?? 'Logout' }}</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-700 text-sm font-medium">{{ $settings['nav_login'] ?? 'Log in' }}</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- CONTACT HEADER -->
    <header class="bg-white border-b py-16 px-4">
        <div class="max-w-7xl mx-auto text-center">
            <h1 class="text-4xl md:text-6xl font-extrabold text-gray-900 mb-4">Contact Us</h1>
            <p class="text-gray-600 max-w-2xl mx-auto text-lg">We are here to help. Reach out to the SUG administration for any inquiries or support.</p>
        </div>
    </header>

    <!-- CONTACT SECTION -->
    <main class="py-12 px-4 max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Contact Info -->
            <div class="lg:col-span-1 space-y-8">
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Get in Touch</h3>
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center shrink-0">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase">Email</p>
                                <p class="text-gray-700 font-medium">support@sug.edu.ng</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center shrink-0">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase">Phone</p>
                                <p class="text-gray-700 font-medium">+234 800 SUG PORTAL</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center shrink-0">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase">Address</p>
                                <p class="text-gray-700 font-medium">SUG Secretariat, University Campus</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="lg:col-span-2">
                <div class="bg-white p-8 md:p-12 rounded-3xl shadow-sm border border-gray-100">
                    <form action="#" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @csrf
                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-gray-600 mb-1">Full Name</label>
                            <input type="text" name="name" required class="px-4 py-3 border rounded-xl outline-none focus:ring-2 focus:ring-blue-500" placeholder="Your name">
                        </div>
                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-gray-600 mb-1">Email Address</label>
                            <input type="email" name="email" required class="px-4 py-3 border rounded-xl outline-none focus:ring-2 focus:ring-blue-500" placeholder="email@example.com">
                        </div>
                        <div class="flex flex-col md:col-span-2">
                            <label class="text-sm font-medium text-gray-600 mb-1">Subject</label>
                            <input type="text" name="subject" required class="px-4 py-3 border rounded-xl outline-none focus:ring-2 focus:ring-blue-500" placeholder="What is this regarding?">
                        </div>
                        <div class="flex flex-col md:col-span-2">
                            <label class="text-sm font-medium text-gray-600 mb-1">Message</label>
                            <textarea name="message" required rows="5" class="px-4 py-3 border rounded-xl outline-none focus:ring-2 focus:ring-blue-500" placeholder="Your message..."></textarea>
                        </div>
                        <div class="md:col-span-2">
                            <button type="submit" class="w-full bg-blue-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                                Send Message <i class="fas fa-paper-plane ml-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- FOOTER -->
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
                    <span>{{ $settings['site_name'] ?? 'SUG Portal' }}</span>
                </a>
                <p class="text-gray-400 max-w-sm mb-6">{{ $settings['footer_description'] ?? 'The official Student Union Government platform dedicated to enhancing the student experience through digitalization, transparency, and accessibility.' }}</p>
                <div class="flex gap-4">
                    <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-blue-600 transition"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-blue-600 transition"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-blue-600 transition"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            <div class="col-span-1">
                <h4 class="text-lg font-bold mb-6">{{ $settings['footer_links_title'] ?? 'Quick Links' }}</h4>
                <ul class="space-y-3 text-gray-400">
                    <li><a href="{{ route('about') }}" class="hover:text-white transition">{{ $settings['footer_about_link'] ?? 'About SUG' }}</a></li>
                    <li><a href="{{ route('news') }}" class="hover:text-white transition">{{ $settings['footer_news_link'] ?? 'Campus News' }}</a></li>
                    <li><a href="{{ route('events') }}" class="hover:text-white transition">{{ $settings['footer_events_link'] ?? 'Official Events' }}</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-white transition">{{ $settings['footer_contact_link'] ?? 'Contact Us' }}</a></li>
                </ul>
            </div>
            <div class="col-span-1">
                <h4 class="text-lg font-bold mb-6">{{ $settings['footer_support_title'] ?? 'Student Support' }}</h4>
                <ul class="space-y-3 text-gray-400">
                    <li><a href="{{ route('login') }}" class="hover:text-white transition">{{ $settings['footer_login_link'] ?? 'Student Login' }}</a></li>
                    <li><a href="#" class="hover:text-white transition">{{ $settings['footer_verify_link'] ?? 'Verify Receipt' }}</a></li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto mt-12 pt-8 border-t border-gray-800 text-center text-gray-500 text-sm">
            &copy; {{ date('Y') }} {{ $settings['site_name'] ?? 'Student Union Government' }} Portal. {{ $settings['footer_copyright'] ?? 'All rights reserved.' }}
        </div>
    </footer>
</body>
</html>
