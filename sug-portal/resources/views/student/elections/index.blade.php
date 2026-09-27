@extends('student.layout')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Live Elections</h1>
        <a href="{{ route('student.dashboard') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors text-sm font-medium">
            Back to Dashboard
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($elections as $election)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition group">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-3 py-1 bg-green-100 text-green-600 text-xs font-bold rounded-full uppercase">Live Now</span>
                        <span class="text-xs text-gray-400">Ends: {{ \Carbon\Carbon::parse($election->end_date)->format('M d, h:i A') }}</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-indigo-600 transition">{{ $election->name }}</h3>
                    <p class="text-gray-600 text-sm mb-6 line-clamp-2">{{ $election->description }}</p>
                    <a href="{{ route('student.elections.vote', $election->id) }}" class="block w-full text-center bg-indigo-600 text-white py-3 rounded-xl font-bold hover:bg-indigo-700 transition shadow-sm">
                        Cast Your Vote
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-20 bg-white rounded-2xl border border-dashed border-gray-300">
                <i class="fas fa-vote-yea text-gray-300 text-5xl mb-4"></i>
                <p class="text-gray-500 text-lg">No live elections at the moment. Check back later!</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
