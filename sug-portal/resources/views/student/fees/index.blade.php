@extends('student.layout')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">My Fee Management</h1>
        <a href="{{ route('student.dashboard') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
            Back to Dashboard
        </a>
    </div>

    <!-- Fees to Pay Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.5 0-3 .5-3 2s1.5 2 3 2 3-1 3-2-1.5-2-3-2z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h10a2 2 0 012 2v14a2 2 0 01-2 2z" />
                </svg>
                Fees to be Paid
            </h3>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50">
                        <tr class="text-xs font-semibold text-gray-400 uppercase tracking-wider">
                            <th class="pb-3">Fee Type</th>
                            <th class="pb-3 text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($requiredFees as $fee)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-3 text-sm font-medium text-gray-700">
                                    {{ $fee->feeType->name ?? 'Unknown Fee' }}
                                </td>
                                <td class="py-3 text-sm font-bold text-gray-900 text-right">
                                    ₦{{ number_format($fee->amount, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="py-6 text-center text-gray-500 text-sm">
                                    No fees assigned for your level/session.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Summary Card -->
        <div class="{{ $requiredFees->isEmpty() ? 'bg-green-600' : 'bg-indigo-900' }} rounded-2xl p-6 text-white shadow-lg transition-colors">
            <h3 class="text-lg font-bold mb-4">{{ $requiredFees->isEmpty() ? 'Payment Status' : 'Payment Summary' }}</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="{{ $requiredFees->isEmpty() ? 'text-green-100' : 'text-indigo-200' }} text-sm">
                        {{ $requiredFees->isEmpty() ? 'Balance Due:' : 'Total Required:' }}
                    </span>
                    <span class="text-xl font-bold">
                        ₦{{ $requiredFees->isEmpty() ? '0.00' : number_format($requiredFees->sum('amount'), 2) }}
                    </span>
                </div>
                <div class="pt-4 border-t {{ $requiredFees->isEmpty() ? 'border-green-500' : 'border-indigo-800' }}">
                    @if($requiredFees->isEmpty())
                        <div class="flex items-center justify-center space-x-2 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-300" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-//-C15.35 12.34 13.5 11.5 12 11.5s-1.5.84-1.5 1.5m-1-1.5V14m1-1.5V14" clip-rule="evenodd" />
                            </svg>
                            <p class="text-sm font-medium text-green-100">You are fully cleared for this session!</p>
                        </div>
                        <a href="{{ route('student.receipts') }}" class="w-full block text-center bg-white text-green-600 py-2 rounded-xl font-bold hover:bg-green-50 transition-colors">
                            View All Receipts
                        </a>
                    @else
                        <p class="text-xs text-indigo-300 mb-4">Ready to make payment? Contact the SUG treasury or use the payment portal when available.</p>
                        <a href="{{ route('student.fees.pay') }}" class="w-full block text-center bg-white text-indigo-900 py-2 rounded-xl font-bold hover:bg-indigo-50 transition-colors">
                            Pay Now
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Payment History Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-800">Payment History</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50">
                    <tr class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Transaction ID</th>
                        <th class="px-6 py-4">Amount</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $payment->created_at->format('d M, Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm font-mono text-gray-500">
                                {{ $payment->transaction_ref }}
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-gray-900">
                                ₦{{ number_format($payment->amount, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{
                                    $payment->status === 'success' ? 'bg-green-100 text-green-700' :
                                    ($payment->status === 'failed' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700')
                                }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($payment->status !== 'success')
                                    <a href="{{ route('student.fees.requery', $payment->transaction_ref) }}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold mr-3">
                                        Requery
                                    </a>
                                @endif
                                <a href="{{ route('student.receipts') }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-semibold">
                                    View Receipt
                                </a>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    No payment records found.
                                </td>
                            </tr>
                        @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            {{ $payments->links() }}
        </div>
    </div>
</div>
@endsection
