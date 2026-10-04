@extends('admin.layout')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Manage Elections</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.elections.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors text-sm font-bold flex items-center gap-1">
                <i class="fas fa-plus"></i> Create Election
            </a>
            <a href="{{ route('student.elections.index') }}" class="bg-gray-100 text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-200 transition-colors text-xs font-bold border border-gray-300 flex items-center gap-1">
                <i class="fas fa-eye"></i> View Live Portal
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Election Name</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Session</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Timeline</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Status</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($elections as $election)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800">{{ $election->name }}</div>
                            <div class="text-xs text-gray-500">{{ Str::limit($election->description, 50) }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $election->session->session_name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <div class="flex flex-col">
                                <span>Start: {{ $election->start_date }}</span>
                                <span>End: {{ $election->end_date }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusColors = [
                                    'Draft' => 'bg-gray-100 text-gray-600',
                                    'Scheduled' => 'bg-blue-100 text-blue-600',
                                    'Open' => 'bg-green-100 text-green-600',
                                    'Closed' => 'bg-red-100 text-red-600',
                                    'Published' => 'bg-purple-100 text-purple-600',
                                ];
                                $color = $statusColors[$election->status] ?? 'bg-gray-100 text-gray-600';
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $color }}">
                                {{ $election->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.elections.show', $election->id) }}" class="text-indigo-600 hover:text-indigo-900 p-2" title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.elections.edit', $election->id) }}" class="text-yellow-600 hover:text-yellow-900 p-2" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.elections.destroy', $election->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this election?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 p-2" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500 italic">
                            No elections found. Create one to get started.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
