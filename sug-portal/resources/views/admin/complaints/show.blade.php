@extends('admin.layout')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Verify Payment Complaint</h1>
            <p class="text-gray-600">Review the transaction details and verify the payment on the merchant dashboard.</p>
        </div>

        <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <div class="space-y-4">
                        <h3 class="text-sm font-semibold text-gray-400 uppercase">Student Details</h3>
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                            <p class="text-gray-800 font-bold text-lg">{{ $complaint->student->user->name }}</p>
                            <p class="text-gray-600 text-sm">{{ $complaint->student->matric_no }}</p>
                            <p class="text-gray-600 text-sm">{{ $complaint->student->user->email }}</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <h3 class="text-sm font-semibold text-gray-400 uppercase">Transaction Details</h3>
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                            <div class="flex justify-between py-1">
                                <span class="text-gray-500 text-sm">Fee:</span>
                                <span class="text-gray-800 font-medium">{{ $complaint->feeStructure->name }}</span>
                            </div>
                            <div class="flex justify-between py-1">
                                <span class="text-gray-500 text-sm">Amount:</span>
                                <span class="text-gray-800 font-bold">₦{{ number_format($complaint->amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between py-1">
                                <span class="text-gray-500 text-sm">Reference:</span>
                                <span class="text-indigo-600 font-mono font-bold">{{ $complaint->transaction_ref }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-8">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l7 14H4.5l7-14zm1.5 10a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                Please verify this transaction reference on the OPay/Bank merchant dashboard before clicking "Verify".
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-6 border-t">
                    <a href="{{ route('admin.payment.complaints.index') }}" class="text-gray-500 hover:text-gray-700 font-medium transition-colors">
                        Back to List
                    </a>
                    <div class="flex space-x-4">
                        <form action="{{ route('admin.payment.complaints.reject', $complaint->id) }}" method="POST" class="inline">
                            @csrf
                            <div class="flex items-center space-x-2">
                                <input type="text" name="admin_notes" placeholder="Reason for rejection..." class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
                                <button type="submit" class="bg-red-50 text-red-600 px-4 py-2 rounded-lg font-bold hover:bg-red-100 transition">
                                    Reject
                                </button>
                            </div>
                        </form>
                        <form action="{{ route('admin.payment.complaints.verify', $complaint->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-green-700 transition shadow-md">
                                Verify & Mark as Paid
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
