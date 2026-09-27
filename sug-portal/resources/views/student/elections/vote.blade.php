@extends('student.layout')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('student.elections.index') }}" class="text-gray-500 hover:text-gray-700 transition">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-800">Voting Ballot: {{ $election->name }}</h1>
        </div>
        <div class="text-right">
            <span class="text-xs text-gray-500 block uppercase font-bold">Deadline</span>
            <span class="text-sm font-medium text-red-600">{{ \Carbon\Carbon::parse($election->end_date)->format('M d, h:i A') }}</span>
        </div>
    </div>

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('student.elections.vote.store', $election->id) }}" method="POST" class="space-y-10">
        @csrf

        @foreach($positions as $position)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800">{{ $position->name }}</h3>
                    <p class="text-xs text-gray-500">{{ $position->description ?? 'Select one candidate for this position' }}</p>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($position->candidates as $candidate)
                        <label class="relative group cursor-pointer">
                            <input type="radio" name="votes[{{ $position->id }}]" value="{{ $candidate->id }}" class="peer hidden" required>
                            <div class="p-4 border-2 rounded-2xl transition-all duration-200 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 hover:border-indigo-200 group">
                                <div class="flex items-center gap-4 mb-4">
                                    <img src="{{ $candidate->photo_path ? asset('storage/'.$candidate->photo_path) : asset('images/default-user.png') }}"
                                         class="h-12 w-12 rounded-full object-cover border-2 border-white shadow-sm">
                                    <div class="overflow-hidden">
                                        <p class="font-bold text-gray-800 truncate">{{ $candidate->student->name ?? 'Unknown' }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ $candidate->student->matric_no ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="text-sm text-gray-600 line-clamp-3 mb-2">
                                    {{ $candidate->manifesto ?? 'No manifesto provided.' }}
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-bold uppercase text-gray-400 tracking-wider">Candidate</span>
                                    <div class="w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center peer-checked:bg-indigo-600 peer-checked:border-indigo-600">
                                        <div class="w-2 h-2 bg-transparent rounded-full peer-checked:bg-white"></div>
                                    </div>
                                </div>
                            </div>
                        </label>
                    @empty
                        <div class="col-span-full text-center py-6 text-gray-400 italic">
                            No approved candidates for this position.
                        </div>
                    @endforelse
                </div>
            </div>
        @endforeach

        <div class="flex flex-col items-center justify-center space-y-4 py-8">
            <p class="text-sm text-gray-500 italic">Please review your choices carefully. Once submitted, your vote cannot be changed.</p>
            <button type="submit" class="bg-indigo-600 text-white px-12 py-4 rounded-2xl font-black text-lg hover:bg-indigo-700 transition shadow-xl transform hover:-translate-y-1">
                Submit Final Ballot
            </button>
        </div>
    </form>
</div>
@endsection
