@extends('admin.layout')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Admin Impersonation</h1>
        <a href="{{ route('admin.dashboard') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors text-sm font-medium">
            Back to Dashboard
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 max-w-2xl mx-auto">
        <div class="mb-8 text-center">
            <div class="bg-indigo-50 p-4 rounded-xl border border-indigo-100">
                <h3 class="text-indigo-800 font-bold">User Impersonation</h3>
                <p class="text-sm text-indigo-600 mt-1">
                    Enter a student's identifier to log into their account. You can use their
                    <strong>Email, Name, Matric Number, or Phone Number</strong>.
                </p>
            </div>
        </div>

        <form action="{{ route('unlock.impersonate') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">User Identifier</label>
                <input type="text" name="identifier" placeholder="Email, Matric No, or Phone..." class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" required>
                @error('identifier') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-between gap-4">
                <button type="submit" class="flex-1 bg-indigo-600 text-white py-3 rounded-lg font-bold hover:bg-indigo-700 transition shadow-md flex items-center justify-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-3-3m0 0l3-3m-3 3h12" />
                    </svg>
                    <span>Login as User</span>
                </button>
            </div>
        </form>

        @if(session('impersonating'))
            <div class="mt-8 pt-8 border-t border-gray-100 text-center">
                <form action="{{ route('unlock.stop') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-semibold flex items-center justify-center mx-auto space-x-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span>Stop Impersonation and return to Admin</span>
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection
