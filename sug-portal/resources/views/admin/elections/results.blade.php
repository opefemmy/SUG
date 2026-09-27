@extends('admin.layout')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.elections.index') }}" class="text-gray-500 hover:text-gray-700 transition">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-800">Election Results: {{ $election->name }}</h1>
        </div>

        @if($election->status !== 'Published')
            <form action="{{ route('admin.elections.results.publish', $election->id) }}" method="POST">
                @csrf
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-green-700 transition shadow-sm flex items-center gap-2">
                    <i class="fas fa-upload"></i> Publish Results
                </button>
            </form>
        @else
            <span class="px-4 py-2 bg-purple-100 text-purple-700 rounded-lg font-bold text-sm flex items-center gap-2">
                <i class="fas fa-check-circle"></i> Results Published
            </span>
        @endif
    </div>

    <div class="grid grid-cols-1 gap-8">
        @foreach($results as $positionId => $candidates)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800">
                        {{ \App\Models\ElectionPosition::find($positionId)->name ?? 'Position' }}
                    </h3>
                </div>

                <div class="p-6 space-y-4">
                    @forelse($candidates as $res)
                        <div class="flex items-center justify-between p-3 rounded-xl border border-gray-50 bg-gray-50/50">
                            <div class="flex items-center gap-3">
                                <img src="{{ $res->candidate->photo_path ? asset('storage/'.$res->candidate->photo_path) : asset('images/default-user.png') }}" class="h-10 w-10 rounded-full object-cover border">
                                <div>
                                    <p class="font-bold text-gray-800">{{ $res->candidate->student->name ?? 'Unknown' }}</p>
                                    <p class="text-xs text-gray-500">{{ $res->candidate->student->matric_no ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-2xl font-black text-indigo-600">{{ $res->total }}</span>
                                <span class="text-xs text-gray-400 ml-1">Votes</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 italic py-4">No votes cast for this position.</p>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
