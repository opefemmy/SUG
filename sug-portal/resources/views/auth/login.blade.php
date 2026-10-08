<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SUG Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            @php $bgImage = \App\Services\SettingsService::get('site_bg_image'); @endphp
            @if($bgImage)
                background-image: url('{{ asset('storage/' . str_replace(storage_path('app/public/'), '', $bgImage)) }}');
            @else
                background-color: #f3f4f6;
            @endif
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        .login-overlay {
            background-color: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(4px);
        }
    </style>
</head>
<body class="flex items-center justify-center h-screen relative">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md login-overlay">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">{{ \App\Services\SettingsService::get('site_name', 'SUG Portal') }} Login</h2>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('logout_message'))
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4 text-center font-medium">
                {{ session('logout_message') }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="login">Matric Number / Email</label>
                <input type="text" name="login" id="login" value="{{ old('login') }}" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('login') border-red-500 @enderror" required>
                @error('login')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Password</label>
                <input type="password" name="password" id="password" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror" required>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-300">
                Login
            </button>
        </form>
        <div class="mt-4 text-center">
            {{-- Registration link removed as requested --}}
        </div>
    </div>

    <footer class="absolute bottom-4 w-full text-center text-xs text-gray-600 font-medium drop-shadow-sm">
        &copy; 2026 EKSCOTECH SUG PORTAL. All rights reserved. <br>
        Powered by the Directorate of ICT
    </footer>
</body>
</html>
