@extends('student.layout')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Error Alert -->
        @if(session('error'))
            <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded shadow-sm animate-fade-in">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
            <div class="bg-indigo-900 px-6 py-8 text-center">
                <h1 class="text-2xl font-bold text-white">Select Payment Method</h1>
                <p class="text-indigo-200 mt-2">Please choose your preferred payment gateway to proceed</p>
            </div>

            <form action="{{ route('student.fees.process') }}" method="POST" class="p-8">
                @csrf
                <input type="hidden" name="fee_id" value="{{ $fee->id }}">

                <div class="grid grid-cols-1 gap-4 mb-8">
                    @foreach($enabledGateways as $gateway)
                        <label class="relative flex items-center p-4 border-2 rounded-xl cursor-pointer transition-all hover:border-indigo-500 group border-gray-200 has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50">
                            <input type="radio" name="gateway" value="{{ $gateway }}"
                                   class="w-5 h-5 text-indigo-600 focus:ring-indigo-500"
                                   onchange="showConfirmation()" required>
                            <div class="ml-4 flex items-center space-x-4">
                                <div class="w-12 h-12 rounded-lg border bg-white overflow-hidden flex items-center justify-center shrink-0 shadow-sm">
                                    @if(isset($gatewayLogos[$gateway]) && $gatewayLogos[$gateway])
                                        <img src="{{ asset('storage/' . $gatewayLogos[$gateway]) }}" class="w-full h-full object-cover">
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7-4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <span class="block text-lg font-bold text-gray-800 group-hover:text-indigo-900">
                                        {{ ucfirst($gateway) }}
                                    </span>
                                    <span class="block text-sm text-gray-500">
                                        Secure payment via {{ ucfirst($gateway) }}
                                    </span>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>

                <!-- Confirmation Section -->
                <div id="payment-confirmation" class="hidden space-y-6 animate-fade-in">
                    <div class="p-6 bg-gray-50 rounded-2xl border border-gray-200">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b">Payment Summary</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-gray-500">Full Name:</span>
                                <span class="font-semibold text-gray-800">{{ $student->user->name }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-gray-500">Matric Number:</span>
                                <span class="font-semibold text-gray-800">{{ $student->matric_no }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-gray-500">Email Address:</span>
                                <span class="font-semibold text-gray-800">{{ $student->user->email }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-gray-500">Payment Reference:</span>
                                <span class="font-mono text-indigo-600 font-bold">{{ strtoupper(uniqid('PAY-')) }}</span>
                            </div>
                            <div class="md:col-span-2 flex justify-between py-3 px-2 bg-indigo-100 rounded-lg mt-2">
                                <span class="text-indigo-800 font-bold">Amount to Pay:</span>
                                <span class="text-xl font-black text-indigo-900">₦{{ number_format($fee->amount, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-4">
                        <a href="{{ route('student.fees') }}" class="text-gray-500 hover:text-gray-700 font-medium transition-colors">
                            &larr; Back to Fees
                        </a>
                        <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg">
                            Proceed to Payment
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function showConfirmation() {
        const confirmation = document.getElementById('payment-confirmation');
        confirmation.classList.remove('hidden');
        confirmation.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
</script>

<style>
    .animate-fade-in {
        animation: fadeIn 0.4s ease-out forwards;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection
