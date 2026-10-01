@extends('admin.layout')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Ticket Details</h1>
            <a href="{{ route('admin.support.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                Back to Tickets
            </a>
        </div>

        <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
            <!-- Ticket Header -->
            <div class="bg-gray-50 p-6 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">{{ $ticket->subject }}</h2>
                    <p class="text-sm text-gray-500">Ticket #{{ $ticket->ticket_number }} • Submitted on {{ $ticket->created_at->format('d M, Y H:i') }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="px-3 py-1 text-xs font-bold rounded-full uppercase tracking-wider
                        {{ $ticket->status === 'open' ? 'bg-blue-100 text-blue-700' : '' }}
                        {{ $ticket->status === 'resolved' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $ticket->status === 'closed' ? 'bg-gray-100 text-gray-700' : '' }}">
                        {{ $ticket->status }}
                    </span>

                    <form action="{{ route('admin.support.update_status', $ticket->id) }}" method="POST" class="inline-flex items-center space-x-2">
                        @csrf
                        <select name="status" onchange="this.form.submit()" class="text-xs border rounded p-1 focus:ring-indigo-500">
                            <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Open</option>
                            <option value="resolved" {{ $ticket->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </form>
                </div>
            </div>

            <!-- Ticket Body -->
            <div class="p-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Student</p>
                        <p class="text-sm font-bold text-gray-800">{{ $ticket->student->user->name }}</p>
                        <p class="text-xs text-gray-600">{{ $ticket->student->matric_no }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Category</p>
                        <p class="text-sm font-bold text-gray-800">{{ $ticket->category->name ?? 'General' }}</p>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-3">Complaint Description</h3>
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-gray-700 leading-relaxed">
                        {{ $ticket->description }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
