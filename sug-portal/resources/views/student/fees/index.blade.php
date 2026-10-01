@extends('student.layout')

@section('content')
<div class="space-y-8">
    <!-- Flash Messages -->
    @if(session('success') || session('error') || session('info'))
        <div class="fixed top-4 right-4 z-50 max-w-md w-full space-y-3">
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded shadow-md flex items-start space-x-3 animate-bounce-in">
                    <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <p class="text-sm font-bold text-green-800">Success</p>
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-600 border-l-4 border-red-800 p-4 rounded shadow-md flex items-start space-x-3 text-white">
                    <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 001.414 1.414L10 11.414V14a1 1 0 102 0v-2.586l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586V7.293z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <p class="text-sm font-bold text-white">Error</p>
                        <p class="text-sm text-red-100">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @if(session('info'))
                <div class="bg-red-600 border-l-4 border-red-800 p-4 rounded shadow-md flex items-start space-x-3 text-white">
                    <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <p class="text-sm font-bold text-white">Information</p>
                        <p class="text-sm text-red-100">{{ session('info') }}</p>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">My Fee Management</h1>
        <a href="{{ route('student.dashboard') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
            Back to Dashboard
        </a>
    </div>

    <!-- Fees to Pay Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-800 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.5 0-3 .5-3 2s1.5 2 3 2 3-1 3-2-1.5-2-3-2z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h10a2 2 0 012 2v14a2 2 0 01-2 2z" />
                    </svg>
                    Fees to be Paid
                </h3>
                <a href="{{ route('student.complaints.create') }}" class="text-xs font-semibold text-red-600 hover:text-red-800 bg-red-50 px-2 py-1 rounded border border-red-100 transition">
                    Report Payment Issue
                </a>
            </div>

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

        <!-- Manual Verification Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-md font-bold text-gray-800 mb-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Verify Manual Payment
            </h3>
            <form action="{{ route('student.fees.verifyManual') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Select Fee Paid</label>
                    <select name="fee_id" class="w-full p-2 text-sm border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" required>
                        <option value="">Select Fee</option>
                        @foreach($verificationFees as $fee)
                            <option value="{{ $fee->id }}">{{ $fee->feeType->name }} (₦{{ number_format($fee->amount, 2) }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Transaction ID</label>
                    <input type="text" name="transaction_id" class="w-full p-2 text-sm border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="e.g. SUG-XXXXX" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Payment Gateway</label>
                    <select name="gateway" class="w-full p-2 text-sm border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" required>
                        <option value="opay">OPay</option>
                        <option value="paystack">Paystack</option>
                        <option value="flutterwave">Flutterwave</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-lg text-sm font-bold hover:bg-indigo-700 transition shadow-sm">
                    Verify Now
                </button>
            </form>
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
