<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Receipt - SUG Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="max-w-md w-full mx-4">
        @if($payment)
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-green-200">
                <div class="bg-green-500 p-6 text-center text-white">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-white text-green-500 rounded-full mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold">Verified Payment</h2>
                    <p class="text-green-100 text-sm">This is an authentic SUG receipt</p>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-500 text-sm">Student</span>
                        <span class="font-semibold text-gray-800">{{ $payment->student->user->name }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-500 text-sm">Matric No</span>
                        <span class="font-semibold text-gray-800">{{ $payment->student->matric_no }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-500 text-sm">Fee Paid</span>
                        <span class="font-semibold text-gray-800">{{ $payment->feeStructure->feeType->name }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-500 text-sm">Amount</span>
                        <span class="font-bold text-green-600 text-lg">₦{{ number_format($payment->amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-gray-500 text-sm">Payment Date</span>
                        <span class="font-semibold text-gray-800">{{ $payment->payment_date ? $payment->payment_date->format('d M, Y') : 'N/A' }}</span>
                    </div>
                </div>
                <div class="bg-gray-50 p-4 text-center">
                    <p class="text-xs text-gray-400">Verification Token: {{ $payment->transaction_ref }}</p>
                </div>
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-red-200">
                <div class="bg-red-500 p-6 text-center text-white">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-white text-red-500 rounded-full mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold">Invalid Receipt</h2>
                    <p class="text-red-100 text-sm">This payment could not be verified</p>
                </div>
                <div class="p-8 text-center">
                    <p class="text-gray-600 mb-6">The receipt number provided does not exist in our records or the payment was unsuccessful.</p>
                    <a href="/" class="inline-block bg-gray-800 text-white px-6 py-2 rounded-lg hover:bg-gray-900 transition">Return Home</a>
                </div>
            </div>
        @endif
    </div>
</body>
</html>
