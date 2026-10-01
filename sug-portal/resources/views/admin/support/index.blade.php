@extends('admin.layout')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800">General Support Tickets</h1>
            <p class="text-gray-600">Manage student support requests and technical complaints.</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white shadow-md rounded-xl overflow-hidden border border-gray-200">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold">
                    <tr class="divide-x divide-gray-200">
                        <th class="px-6 py-4">Student</th>
                        <th class="px-6 py-4">Category</th>
                        <th class="px-6 py-4">Subject</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Date</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($tickets as $ticket)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-800">{{ $ticket->student->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $ticket->student->matric_no }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">
                                    {{ $ticket->category->name ?? 'General' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-700">{{ $ticket->subject }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                    {{ $ticket->status === 'open' ? 'bg-blue-100 text-blue-700' : '' }}
                                    {{ $ticket->status === 'resolved' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $ticket->status === 'closed' ? 'bg-gray-100 text-gray-700' : '' }}
                                    {{ !in_array($ticket->status, ['open', 'resolved', 'closed']) ? 'bg-yellow-100 text-yellow-700' : '' }}">
                                    {{ ucfirst($ticket->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right text-sm text-gray-500">{{ $ticket->created_at->format('d M, Y') }}</td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('admin.support.show', $ticket->id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium text-sm">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                No support tickets found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $tickets->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
