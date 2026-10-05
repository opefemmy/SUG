<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-200">
        <div class="p-8">
            <div class="text-center mb la-8">
                <h2 class="text-3xl font-bold text-gray-800">Create Account</h2>
                <p class="text-gray-500 mt-2">Join the SUG Portal community</p>
            </div>

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg text-sm text-center">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg text-sm">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" x-data="{ role: 'student' }" class="space-y-5">
                @csrf

                <!-- Role Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">I am a:</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="student" x-model="role" class="sr-only peer">
                            <div class="p-3 text-center rounded-lg border-2 border-gray-200 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 text-gray-600 peer-checked:text-indigo-600 font-medium transition-all">
                                Student
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="admin" x-model="role" class="sr-only peer">
                            <div class="p-3 text-center rounded-lg border-2 border-gray-200 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 text-gray-600 peer-checked:text-indigo-600 font-medium transition-all">
                                Admin
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Basic Info -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Password</label>
                            <input type="password" name="password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                            <input type="password" name="password_confirmation" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                </div>

                <!-- Student Specific Fields -->
                <div x-show="role === 'student'" x-transition class="space-y-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-600 mb-3">Student Academic Details</h3>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Matric Number</label>
                        <input type="text" name="matric_no" x-show="role === 'student'" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Department</label>
                            <select name="department_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select Dept</option>
                                @foreach(\App\Models\Department::all() as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Level</label>
                            <select name="level_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select Level</option>
                                @foreach(\App\Models\Level::all() as $level)
                                    <option value="{{ $level->id }}">{{ $level->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg transition-colors shadow-lg">
                    Create Account
                </button>

                <div class="text-center mt-6">
                    <p class="text-sm text-gray-600">Already have an account? <a href="{{ route('login') }}" class="text-indigo-600 font-medium hover:underline">Login here</a></p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
