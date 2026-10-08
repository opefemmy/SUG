@extends('student.layout')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Submit Payment Complaint</h1>
            <p class="text-gray-600">Provide the details of your successful payment that isn't reflecting in your account.</p>
        </div>

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
            <form action="{{ route('student.complaints.store') }}" method="POST" class="p-8 space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fee Type</label>
                    <select name="fee_structure_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
                        <option value="">-- Select Fee --</option>
                        @foreach($fees as $fee)
                            <option value="{{ $fee->id }}">{{ $fee->feeType->name ?? 'Unknown Fee' }}</option>
                        @endforeach
                    </select>
                    @error('fee_structure_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Complaint Category</label>
                        <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
                            <option value="">-- Select Category --</option>
                            <option value="Payment Not Reflecting">Payment Not Reflecting</option>
                            <option value="Double Debit">Double Debit</option>
                            <option value="Incorrect Amount">Incorrect Amount</option>
                            <option value="Payment Failed but Debited">Payment Failed but Debited</option>
                            <option value="Receipt Issue">Receipt Issue</option>
                            <option value="Other">Other</option>
                        </select>
                        @error('category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subject / Detailed Description</label>
                        <textarea name="subject" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="Briefly describe the issue..."></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Amount Paid (₦)</label>
                        <input type="number" step="0.01" name="amount" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
                        @error('amount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date of Payment</label>
                        <input type="date" name="payment_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
                        @error('payment_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Transaction Reference</label>
                    <input type="text" name="transaction_ref" placeholder="e.g. SUG-XXXXX" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
                    <p class="text-xs text-gray-500 mt-1">This is the reference number provided by the payment gateway.</p>
                    @error('transaction_ref') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-between pt-6 border-t">
                    <a href="{{ route('student.complaints.index') }}" class="text-gray-500 hover:text-gray-700 font-medium transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg">
                        Submit Complaint
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
