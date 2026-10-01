<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impersonation Dashboard - SUG Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="flex items-center justify-center min-h-screen py-12">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 max-w-2xl w-full">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-2xl font-bold text-gray-800">Impersonation Dashboard</h1>
                <form action="{{ route('unlock.stop') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-red-600 transition">
                        Stop Impersonating / Logout
                    </button>
                </form>
            </div>

            <div class="mb-8 text-center">
                <div class="bg-indigo-50 p-4 rounded-xl border border-indigo-100">
                    <h3 class="text-indigo-800 font-bold">User Impersonation</h3>
                    <p class="text-sm text-indigo-600 mt-1">
                        Enter a student's identifier to log into their account. You can use their
                        <strong class="text-indigo-800">Email, Name, Matric Number, or Phone Number</strong>.
                    </p>
                </div>
            </div>

            <form action="{{ route('unlock.impersonate') }}" method="POST" class="space-y-6">
                @csrf
                <div class="flex gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">User Identifier</label>
                        <input type="text" name="identifier" placeholder="Email, Matric No, or Phone..." class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" required>
                        @error('identifier') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-indigo-700 transition shadow-md">
                            Login as User
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
