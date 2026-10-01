@extends('admin.layout')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Payment Complaints</h1>
            <p class="text-gray-600">Review and verify student payment issues.</p>
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
                        <th class="px-6 py-4">Fee</th>
                        <th class="px-6 py-4">Reference</th>
                        <th class="px-6 py-4">Amount</th>
                        <th class="px-6 py-4">Submitted</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($complaints as $complaint)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-800">{{ $complaint->student->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $complaint->student->matric_no }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">
                                    {{ $complaint->category ?? 'Uncategorized' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-700">{{ $complaint->feeStructure->name }}</td>
                            <td class="px-6 py-4 font-mono text-sm text-gray-600">{{ $complaint->transaction_ref }}</td>
                            <td class="px-6 py-4 text-gray-800">₦{{ number_format($complaint->amount, 2) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $complaint->created_at->format('d M Y H:i') }}</td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('admin.payment.complaints.show', $complaint->id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium text-sm">View</a>
                                <form action="{{ route('admin.payment.complaints.verify', $complaint->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-900 font-medium text-sm">Verify</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                                No pending payment complaints found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $complaints->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
