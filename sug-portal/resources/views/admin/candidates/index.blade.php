@extends('admin.layout')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Manage Candidates</h1>
        <a href="{{ route('admin.candidates.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors flex items-center gap-2">
            <i class="fas fa-plus"></i> Add New Candidate
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-6">
        <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <form action="{{ route('admin.candidates.index') }}" method="GET" class="flex gap-2">
                <select name="election_id" class="p-2 border rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Elections</option>
                    @foreach($elections as $election)
                        <option value="{{ $election->id }}" {{ request('election_id') == $election->id ? 'selected' : '' }}>
                            {{ $election->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="bg-gray-100 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition">Filter</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr class="text-gray-600 text-sm font-semibold">
                        <th class="px-6 py-4">Candidate</th>
                        <th class="px-6 py-4">Election</th>
                        <th class="px-6 py-4">Position</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($candidates as $candidate)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $candidate->photo_path ? asset('storage/'.$candidate->photo_path) : asset('images/default-user.png') }}" class="h-10 w-10 rounded-full object-cover border">
                                    <div>
                                        <div class="font-bold text-gray-800">{{ $candidate->student->user->name ?? 'Unknown' }}</div>
                                        <div class="text-xs text-gray-500">{{ $candidate->student->department->name ?? 'N/A' }} | {{ $candidate->student->matric_no ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $candidate->election->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $candidate->position->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-600',
                                        'approved' => 'bg-green-100 text-green-600',
                                        'rejected' => 'bg-red-100 text-red-600',
                                    ];
                                    $color = $statusColors[$candidate->approval_status] ?? 'bg-gray-100 text-gray-600';
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $color }}">
                                    {{ ucfirst($candidate->approval_status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                @if($candidate->approval_status === 'pending')
                                    <form action="{{ route('admin.candidates.approve', $candidate->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-green-600 hover:text-green-800 p-2" title="Approve">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.candidates.reject', $candidate->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-red-600 hover:text-red-800 p-2" title="Reject">
                                            <i class="fas fa-times-circle"></i>
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.candidates.edit', $candidate->id) }}" class="text-yellow-600 hover:text-yellow-800 p-2" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.candidates.destroy', $candidate->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 p-2" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500 italic">
                                No candidates found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
