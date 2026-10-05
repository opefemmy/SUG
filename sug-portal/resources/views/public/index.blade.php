<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1,0">
    <title>{{ $settings['site_name'] ?? 'SUG Portal - Student Union Government' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <style>
        .swiper {
            width: 100%;
            height: 700px;
        }
        .swiper-slide {
            position: relative;
            overflow: hidden;
        }
        .slide-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            z-index: -2;
            transition: transform 6s ease-in-out;
        }
        .swiper-slide-active .slide-bg {
            transform: scale(1.1);
        }
        .slide-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Flip Box Styles */
        .flip-box {
            perspective: 1000px;
            height: 300px;
        }
        .flip-box-inner {
            position: relative;
            width: 100%;
            height: 100%;
            text-align: center;
            transition: transform 0.6s;
            transform-style: preserve-3d;
            cursor: pointer;
        }
        .flip-box:hover .flip-box-inner {
            transform: rotateY(180deg);
        }
        .flip-box-front, .flip-box-back {
            position: absolute;
            width: 100%;
            height: 100%;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            border-radius: 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .flip-box-front {
            background-color: white;
            border: 1px solid #e5e7eb;
        }
        .flip-box-back {
            background-color: #1e40af;
            color: white;
            transform: rotateY(180deg);
        }
    </style>
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
                        <a href="{{ route('contact') }}" class="text-gray-600 hover:text-blue-700 px-3 py-2 rounded-md text-sm font-medium">{{ $settings['nav_contact'] ?? 'Contact' }}</a>
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
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-gray-600 hover:text-blue-700 text-sm font-medium ml-4">{{ $settings['nav_register'] ?? 'Register' }}</a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <div class="relative">
        @php
            $slides = \App\Models\HeroSlider::orderBy('sort_order', 'asc')->get();
        @endphp

        @if(count($slides) > 0)
            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    @foreach($slides as $slide)
                        <div class="swiper-slide">
                            <div class="slide-bg" style="background-image: url('{{ asset('storage/' . $slide->image_path) }}')"></div>
                            <div class="slide-overlay" style="background-color: {{ $slide->overlay_color ?? 'rgba(0,0,0,0.4)' }}; background-image: linear-gradient(135deg, rgba(0,0,0,0.6) 0%, rgba(0,0,0,0.2) 100%);"></div>

                            <div class="relative z-10 flex items-center justify-center h-full text-center text-white px-4">
                                <div class="max-w-4xl glass-card p-8 md:p-12 rounded-3xl animate-fade-in-up">
                                    <h1 class="text-4xl md:text-7xl font-black mb-6 leading-tight tracking-tight drop-shadow-2xl">
                                        {{ $slide->title ?? $settings['hero_title'] ?? 'Empowering Students' }}
                                    </h1>
                                    <p class="text-lg md:text-2xl text-blue-50 mb-10 max-w-2xl mx-auto font-light leading-relaxed drop-shadow-md">
                                        {{ $slide->subtitle ?? $settings['hero_subtitle'] ?? 'The official digital hub for the Student Union Government.' }}
                                    </p>
                                    <div class="flex flex-col sm:flex-row justify-center gap-6">
                                        @if($slide->cta_primary_text)
                                            <a href="{{ $slide->cta_primary_url ?? '#' }}" class="bg-white text-blue-900 px-10 py-4 rounded-full font-bold text-xl hover:scale-105 transition-all duration-300 shadow-2xl">
                                                {{ $slide->cta_primary_text }}
                                            </a>
                                        @endif
                                        @if($slide->cta_secondary_text)
                                            <a href="{{ $slide->cta_secondary_url ?? '#' }}" class="bg-transparent border-2 border-white text-white px-10 py-4 rounded-full font-bold text-xl hover:bg-white hover:text-blue-900 transition-all duration-300 backdrop-blur-sm">
                                                {{ $slide->cta_secondary_text }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        @else
            <!-- Fallback to existing blue background -->
            <header class="relative bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 text-white py-20 px-4 overflow-hidden h-[700px] flex items-center justify-center">
                <div class="max-w-7xl mx-auto relative z-10 text-center">
                    <h1 class="text-4xl md:text-7xl font-black mb-6 leading-tight drop-shadow-2xl">
                        {{ $settings['hero_title'] ?? 'Empowering Students, Building the Future.' }}
                    </h1>
                    <p class="text-lg md:text-2xl text-blue-100 mb-10 max-w-2xl mx-auto font-light leading-relaxed drop-shadow-md">
                        {{ $settings['hero_subtitle'] ?? 'The official digital hub for the Student Union Government. Manage your dues, stay updated with campus news, and exercise your democratic right to vote.' }}
                    </p>
                    <div class="flex flex-col sm:flex-row justify-center gap-6">
                        <a href="{{ route('login') }}" class="bg-white text-blue-900 px-10 py-4 rounded-full font-bold text-xl hover:scale-105 transition-all duration-300 shadow-2xl">
                            {{ $settings['hero_cta_primary'] ?? 'Get Started' }}
                        </a>
                        <a href="{{ route('about') }}" class="bg-transparent border-2 border-white text-white px-10 py-4 rounded-full font-bold text-xl hover:bg-white hover:text-blue-900 transition-all duration-300 backdrop-blur-sm">
                            {{ $settings['hero_cta_secondary'] ?? 'Learn More' }}
                        </a>
                    </div>
                </div>
                <div class="absolute top-0 right-0 -translate-y-12 translate-x-12 w-96 h-96 bg-blue-500 rounded-full filter blur-3xl opacity-20"></div>
                <div class="absolute bottom-0 left-0 translate-y-12 -translate-x-12 w-96 h-96 bg-blue-400 rounded-full filter blur-3xl opacity-20"></div>
            </header>
        @endif
    </div>

    <!-- FLIP BOX SECTION -->
    <section class="py-20 bg-white px-4">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Why Choose Our Portal?</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Experience the most transparent and efficient way to manage your student union affairs.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Flip Box 1 -->
                <div class="flip-box">
                    <div class="flip-box-inner">
                        <div class="flip-box-front">
                            <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mb-6">
                                <i class="fas fa-bolt text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-bold mb-2">Fast Payments</h3>
                            <p class="text-gray-500 text-sm">Pay your dues in seconds using secure gateways.</p>
                        </div>
                        <div class="flip-box-back">
                            <h3 class="text-xl font-bold mb-4">Instant Receipts</h3>
                            <p class="text-blue-100 text-sm mb-6">No more queues. Get your official verified receipt instantly upon payment.</p>
                            <a href="{{ route('login') }}" class="bg-white text-blue-700 px-4 py-2 rounded-full text-xs font-bold hover:bg-blue-50 transition">Pay Now</a>
                        </div>
                    </div>
                </div>
                <!-- Flip Box 2 -->
                <div class="flip-box">
                    <div class="flip-box-inner">
                        <div class="flip-box-front">
                            <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mb-6">
                                <i class="fas fa-shield-alt text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-bold mb-2">Secure Voting</h3>
                            <p class="text-gray-500 text-sm">Your vote is your voice, and we keep it safe.</p>
                        </div>
                        <div class="flip-box-back">
                            <h3 class="text-xl font-bold mb-4">Absolute Privacy</h3>
                            <p class="text-green-100 text-sm mb-6">End-to-end encrypted voting ensures your choice remains anonymous.</p>
                            <a href="{{ route('login') }}" class="bg-white text-green-700 px-4 py-2 rounded-full text-xs font-bold hover:bg-green-50 transition">Vote Now</a>
                        </div>
                    </div>
                </div>
                <!-- Flip Box 3 -->
                <div class="flip-box">
                    <div class="flip-box-inner">
                        <div class="flip-box-front">
                            <div class="w-16 h-16 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center mb-6">
                                <i class="fas fa-info-circle text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-bold mb-2">Real-time News</h3>
                            <p class="text-gray-500 text-sm">Stay updated with the latest campus events.</p>
                        </div>
                        <div class="flip-box-back">
                            <h3 class="text-xl font-bold mb-4">Always Informed</h3>
                            <p class="text-purple-100 text-sm mb-6">Get instant notifications on SUG activities and university policies.</p>
                            <a href="{{ route('news') }}" class="bg-white text-purple-700 px-4 py-2 rounded-full text-xs font-bold hover:bg-purple-50 transition">Read News</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURE GRID -->
    <section class="py-20 px-4 max-w-7xl mx-auto">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ $settings['services_title'] ?? 'Our Student Services' }}</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">{{ $settings['services_subtitle'] ?? 'Everything you need to navigate your university life seamlessly in one place.' }}</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Fee Payment -->
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition group">
                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mb-6 group-hover:bg-blue-600 group-hover:text-white transition">
                    <i class="fas fa-credit-card text-xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">{{ $settings['feature_fees_title'] ?? 'Fee Management' }}</h3>
                <p class="text-gray-600 mb-6">{{ $settings['feature_fees_desc'] ?? 'Quickly pay your SUG dues and download verified receipts instantly.' }}</p>
                <a href="{{ route('login') }}" class="text-blue-700 font-semibold flex items-center gap-2 hover:underline">
                    {{ $settings['feature_fees_cta'] ?? 'Pay Now' }} <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition group">
                <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center mb-6 group-hover:bg-green-600 group-hover:text-white transition">
                    <i class="fas fa-vote-yea text-xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">{{ $settings['feature_voting_title'] ?? 'Online Voting' }}</h3>
                <p class="text-gray-600 mb-6">{{ $settings['feature_voting_desc'] ?? 'Participate in SUG elections securely and anonymously from your device.' }}</p>
                <a href="{{ route('login') }}" class="text-green-700 font-semibold flex items-center gap-2 hover:underline">
                    {{ $settings['feature_voting_cta'] ?? 'Vote Now' }} <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition group">
                <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center mb-6 group-hover:bg-purple-600 group-hover:text-white transition">
                    <i class="fas fa-bullhorn text-xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">{{ $settings['feature_news_title'] ?? 'Campus News' }}</h3>
                <p class="text-gray-600 mb-6">{{ $settings['feature_news_desc'] ?? 'Stay informed with the latest announcements, news, and official events.' }}</p>
                <a href="{{ route('news') }}" class="text-purple-700 font-semibold flex items-center gap-2 hover:underline">
                    {{ $settings['feature_news_cta'] ?? 'View News' }} <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- LATEST UPDATES SECTION -->
    <section class="py-20 bg-gray-100 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-end mb-12">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">{{ $settings['updates_title'] ?? 'Latest Campus Updates' }}</h2>
                    <p class="text-gray-600">{{ $settings['updates_subtitle'] ?? 'Keep up to date with what\'s happening on campus.' }}</p>
                </div>
                <a href="{{ route('news') }}" class="hidden md:block text-blue-700 font-bold hover:underline">{{ $settings['updates_cta'] ?? 'View All News' }}</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($news as $item)
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition">
                        <div class="p-6">
                            <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">{{ $item->category->name ?? 'General' }}</span>
                            <h3 class="text-xl font-bold mt-2 mb-3">{{ $item->title }}</h3>
                            <p class="text-gray-600 text-sm line-clamp-3 mb-4">{{ Str::limit($item->content, 100) }}</p>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-400"><i class="far fa-calendar-alt mr-1"></i> {{ $item->created_at->format('M d, Y') }}</span>
                                <a href="{{ route('news.show', $item->slug) }}" class="text-blue-700 text-sm font-bold hover:underline">{{ $settings['label_read_more'] ?? 'Read More' }}</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 italic col-span-3 text-center py-10">{{ $settings['label_no_news'] ?? 'No recent news available.' }}</p>
                @endforelse
            </div>
        </div>
    </section>

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
            <div>
                <h4 class="text-lg font-bold mb-6">{{ $settings['footer_links_title'] ?? 'Quick Links' }}</h4>
                <ul class="space-y-3 text-gray-400">
                    <li><a href="{{ route('about') }}" class="hover:text-white transition">{{ $settings['footer_about_link'] ?? 'About SUG' }}</a></li>
                    <li><a href="{{ route('news') }}" class="hover:text-white transition">{{ $settings['footer_news_link'] ?? 'Campus News' }}</a></li>
                    <li><a href="{{ route('events') }}" class="hover:text-white transition">{{ $settings['footer_events_link'] ?? 'Official Events' }}</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-white transition">{{ $settings['footer_contact_link'] ?? 'Contact Us' }}</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-lg font-bold mb-6">{{ $settings['footer_support_title'] ?? 'Student Support' }}</h4>
                <ul class="space-y-3 text-gray-400">
                    <li><a href="{{ route('login') }}" class="hover:text-white transition">{{ $settings['footer_login_link'] ?? 'Student Login' }}</a></li>
                    <li><a href="#" class="hover:text-white transition">{{ $settings['footer_verify_link'] ?? 'Verify Receipt' }}</a></li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto mt-12 pt-8 border-t border-gray-800 text-center text-gray-500 text-sm">
            &copy; {{ date('Y') }} {{ $settings['site_name'] ?? 'Student Union Government' }} Portal. {{ $settings['footer_copyright'] ?? 'All rights reserved.' }}
            <div class="mt-2 font-medium text-gray-400">
                Powered by the Directorate of ICT
            </div>
        </div>
    </footer>

    @include('public.index_scripts')
</body>
</html>
