@extends('public.layout')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4">
    <div class="max-w-2xl mx-auto bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
        <!-- Header -->
        <div class="bg-indigo-600 p-6 text-center text-white">
            <h1 class="text-2xl font-bold">Payment Verification</h1>
            <p class="text-indigo-100 text-sm mt-1">Official SUG Portal Receipt Validation</p>
        </div>

        <div class="p-8 relative">
            <!-- Watermark Logo -->
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none overflow-hidden opacity-5 select-none">
                @php $logo = \App\Services\SettingsService::get('brand_logo'); @endphp
                @if($logo)
                    <img src="{{ asset('storage/' . $logo) }}" class="w-96 h-96 object-contain rotate-12" alt="Watermark">
                @else
                    <i class="fas fa-university text-[200px] rotate-12"></i>
                @endif
            </div>

            <!-- Student Photo Section -->
            <div class="flex flex-col items-center mb-8">
                <div class="relative">
                    <div class="w-32 h-32 rounded-full border-4 border-indigo-500 overflow-hidden bg-gray-200 shadow-lg">
                        @if($payment->student->biodata && $payment->student->biodata->passport_path)
                            <img src="{{ asset('storage/' . $payment->student->biodata->passport_path) }}" class="w-full h-full object-cover object-top" alt="Student Passport">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="absolute bottom-0 right-0 bg-green-500 text-white rounded-full p-1 border-2 border-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.658-7.658a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <h2 class="mt-4 text-xl font-bold text-gray-900">{{ $payment->student->user->name }}</h2>
                <p class="text-gray-500 text-sm">{{ $payment->student->user->email }}</p>
            </div>

            <!-- Verification Details -->
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Matric Number</p>
                        <p class="text-sm font-bold text-gray-900">{{ $payment->student->matric_no }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Programme</p>
                        <p class="text-sm font-bold text-gray-900">{{ $payment->student->programme->name ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Current Level</p>
                        <p class="text-sm font-bold text-gray-900">{{ $payment->student->level->level_number ?? 'N/A' }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Payment Status</p>
                        <p class="text-sm font-bold text-green-600 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            Verified & Paid
                        </p>
                    </div>
                </div>
                <div class="p-4 bg-indigo-50 rounded-xl border border-indigo-100 text-center">
                    <p class="text-xs text-indigo-500 uppercase font-semibold mb-1">Transaction Reference</p>
                    <p class="text-sm font-mono font-bold text-indigo-900">{{ $payment->transaction_ref }}</p>
                </div>
            </div>

            <div class="mt-8 text-center">
                <p class="text-xs text-gray-400">This is an officially generated verification page. Any alterations are fraudulent.</p>
                <div class="mt-4 flex justify-center">
                    <a href="{{ route('home') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-bold flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Portal
                    </a>
                </div>
                <div class="mt-6 pt-4 border-t border-gray-100 text-center text-xs text-gray-400">
                    &copy; 2026 EKSCOTECH SUG PORTAL Portal. All rights reserved. <br>
                    Powered by the Directorate of ICT
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
