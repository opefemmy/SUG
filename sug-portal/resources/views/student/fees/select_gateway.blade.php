@extends('student.layout')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
            <div class="bg-indigo-900 px-6 py-8 text-center">
                <h1 class="text-2xl font-bold text-white">Select Payment Method</h1>
                <p class="text-indigo-200 mt-2">Please choose your preferred payment gateway to proceed</p>
            </div>

            <form action="{{ route('student.fees.process') }}" method="POST" class="p-8">
                @csrf
                <input type="hidden" name="fee_id" value="{{ request('fee_id') }}">

                <div class="grid grid-cols-1 gap-4">
                    @foreach($enabledGateways as $gateway)
                        <label class="relative flex items-center p-4 border-2 rounded-xl cursor-pointer transition-all hover:border-indigo-500 group border-gray-200 has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50">
                            <input type="radio" name="gateway" value="{{ $gateway }}" class="w-5 h-5 text-indigo-600 focus:ring-indigo-500" required>
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
                            <div class="text-indigo-600 opacity-0 group-hover:opacity-100 transition-opacity">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </label>
                    @endforeach
                </div>

                <div class="mt-8 flex items-center justify-between">
                    <a href="{{ route('student.fees') }}" class="text-gray-500 hover:text-gray-700 font-medium transition-colors">
                        &larr; Back to Fees
                    </a>
                    <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg">
                        Proceed to Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
