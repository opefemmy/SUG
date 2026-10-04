@extends('admin.layout')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Election Details</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.elections.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors text-sm font-medium">
                Back to List
            </a>
            <a href="{{ route('admin.elections.edit', $election->id) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                Edit Election
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Election Info -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">General Information</h3>
                <div class="space-y-4">
                    <div>
                        <label class="text-xs text-gray-500 uppercase font-bold">Name</label>
                        <p class="text-gray-800 font-medium">{{ $election->name }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 uppercase font-bold">Session</label>
                        <p class="text-gray-800 font-medium">{{ $election->session->session_name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 uppercase font-bold">Status</label>
                        <span class="px-2 py-1 rounded-full text-xs font-bold {{ $election->status === 'Open' ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-600' }}">
                            {{ $election->status }}
                        </span>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 uppercase font-bold">Timeline</label>
                        <p class="text-sm text-gray-800">Start: {{ $election->start_date }}</p>
                        <p class="text-sm text-gray-800">End: {{ $election->end_date }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 uppercase font-bold">Description</label>
                        <p class="text-sm text-gray-600">{{ $election->description ?? 'No description provided.' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Positions and Candidates -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Positions & Candidates</h3>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.elections.positions.create', $election->id) }}" class="bg-indigo-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-indigo-700 transition flex items-center gap-1">
                            <i class="fas fa-plus"></i> Add Position
                        </a>
                        <a href="{{ route('admin.candidates.index', ['election_id' => $election->id]) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-bold flex items-center gap-1">
                            <i class="fas fa-user-plus"></i> Manage Candidates
                        </a>
                    </div>
                </div>


                <div class="space-y-8">
                    @forelse($election->positions as $position)
                        <div class="border rounded-xl p-4 bg-gray-50">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-bold text-gray-800">{{ $position->name }}</h4>
                                <span class="text-xs text-gray-500">{{ $position->candidates->count() }} Candidates</span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                @forelse($position->candidates as $candidate)
                                    <div class="bg-white p-3 rounded-lg border border-gray-200 flex items-center gap-3">
                                        <img src="{{ $candidate->photo_path ? asset('storage/'.$candidate->photo_path) : asset('images/default-user.png') }}" class="h-10 w-10 rounded-full object-cover border">
                                        <div class="overflow-hidden">
                                            <p class="text-sm font-bold text-gray-800 truncate">{{ $candidate->student->name ?? 'Unknown Student' }}</p>
                                            <span class="text-[10px] px-2 py-0.5 rounded-full font-bold {{ $candidate->approval_status === 'approved' ? 'bg-green-100 text-green-600' : 'bg-yellow-100 text-yellow-600' }}">
                                                {{ ucfirst($candidate->approval_status) }}
                                            </span>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-xs text-gray-400 italic">No candidates registered yet.</p>
                                @endforelse
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <i class="fas fa-folder-open text-gray-300 text-4xl mb-3"></i>
                            <p class="text-gray-500">No positions defined for this election yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
