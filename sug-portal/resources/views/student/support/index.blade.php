@extends('student.layout')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Support & Complaints</h1>
        <a href="{{ route('student.dashboard') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
            Back to Dashboard
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Complaint Form -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-8">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Log a Complaint</h3>

                @if(session('success'))
                    <div class="mb-4 p-3 bg-green-50 border-l-4 border-green-400 text-green-700 text-sm rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('student.support.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Category</label>
                        <select name="category_id" class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition-all" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Subject</label>
                        <input type="text" name="subject" class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition-all" placeholder="Brief summary of the issue" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Description</label>
                        <textarea name="description" rows="4" class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition-all" placeholder="Please describe your complaint in detail..." required></textarea>
                    </div>
                    <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg transform hover:-translate-y-1">
                        Submit Complaint
                    </button>
                </form>
            </div>
        </div>

        <!-- Ticket History -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800">My Support Tickets</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50">
                            <tr class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="px-6 py-4">Ticket No</th>
                                <th class="px-6 py-4">Subject</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($tickets as $ticket)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-sm font-mono font-bold text-indigo-600">
                                        {{ $ticket->ticket_number }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $ticket->subject }}</div>
                                        <div class="text-xs text-gray-500">{{ $ticket->category->name ?? 'General' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                            {{ $ticket->status === 'open' ? 'bg-blue-100 text-blue-700' : '' }}
                                            {{ $ticket->status === 'resolved' ? 'bg-green-100 text-green-700' : '' }}
                                            {{ $ticket->status === 'closed' ? 'bg-gray-100 text-gray-700' : '' }}
                                            {{ !in_array($ticket->status, ['open', 'resolved', 'closed']) ? 'bg-yellow-100 text-yellow-700' : '' }}">
                                            {{ ucfirst($ticket->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm text-gray-500">
                                        {{ $ticket->created_at->format('d M, Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                        No support tickets found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                    {{ $tickets->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
