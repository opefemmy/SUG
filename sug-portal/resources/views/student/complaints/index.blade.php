@extends('student.layout')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">My Payment Complaints</h1>
                <p class="text-gray-600">Track the status of your payment verification requests.</p>
            </div>
            <a href="{{ route('student.complaints.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-indigo-700 transition">
                Submit New Complaint
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow-md rounded-xl overflow-hidden border border-gray-200">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold">
                    <tr>
                        <th class="px-6 py-4">Fee</th>
                        <th class="px-6 py-4">Category</th>
                        <th class="px-6 py-4">Reference</th>
                        <th class="px-6 py-4">Amount</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($complaints as $complaint)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-medium text-gray-800">{{ $complaint->feeStructure->feeType->name ?? 'Unknown Fee' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded-full">
                                    {{ $complaint->category ?? 'General' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-mono text-sm text-gray-600">{{ $complaint->transaction_ref }}</td>
                            <td class="px-6 py-4 text-gray-800">₦{{ number_format($complaint->amount, 2) }}</td>
                            <td class="px-6 py-4">
                                @if($complaint->status === 'pending')
                                    <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-700 rounded-full">Pending</span>
                                @elseif($complaint->status === 'verified')
                                    <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">Verified</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-700 rounded-full">Rejected</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $complaint->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                No payment complaints found.
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
