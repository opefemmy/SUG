@extends('admin.layout')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Bulk Student Promotion</h1>
        <a href="{{ route('admin.students.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors text-sm font-medium">
            Back to Students
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 max-w-2xl mx-auto">
        <div class="mb-8 text-center">
            <div class="bg-indigo-50 p-4 rounded-xl border border-indigo-100">
                <h3 class="text-indigo-800 font-bold">How it works</h3>
                <p class="text-sm text-indigo-600 mt-1">
                    All students in the Source Session will be moved to the Destination Session.
                    Students at <strong>ND I</strong> will be promoted to <strong>ND II</strong>, and
                    <strong>HND I</strong> will be promoted to <strong>HND II</strong>.
                </p>
            </div>
        </div>

        <form action="{{ route('admin.promotion.process') }}" method="POST" onsubmit="return confirm('Are you sure you want to promote all students from the source session? This action cannot be undone.');">
            @csrf
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Source Session (Promote From)</label>
                        <select name="source_session_id" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" required>
                            <option value="">Select Session</option>
                            @foreach($sessions as $session)
                                <option value="{{ $session->id }}" {{ old('source_session_id') == $session->id ? 'selected' : '' }}>{{ $session->session_name }}</option>
                            @endforeach
                        </select>
                        @error('source_session_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Destination Session (Move To)</label>
                        <select name="destination_session_id" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" required>
                            <option value="">Select Session</option>
                            @foreach($sessions as $session)
                                <option value="{{ $session->id }}" {{ old('destination_session_id') == $session->id ? 'selected' : '' }}>{{ $session->session_name }}</option>
                            @endforeach
                        </select>
                        @error('destination_session_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex justify-center pt-4">
                    <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-indigo-700 transition shadow-md flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        <span>Promote Students Now</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
