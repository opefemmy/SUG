<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Unlock - SUG Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="flex items-center justify-center min-h-screen py-12">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 max-w-md w-full">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-indigo-100 text-indigo-600 rounded-full mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-800">System Unlock</h1>
                <p class="text-sm text-gray-500 mt-2">Enter master credentials to access the impersonation dashboard.</p>
            </div>

            <form action="{{ route('unlock.auth') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Master Username</label>
                    <input type="text" name="username" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" required>
                    @error('username') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Master Password</label>
                    <input type="password" name="password" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" required>
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg font-bold hover:bg-indigo-700 transition shadow-md">
                    Unlock Dashboard
                </button>
            </form>
        </div>
    </div>
</body>
</html>
