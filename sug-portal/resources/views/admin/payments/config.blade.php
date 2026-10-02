@extends('admin.layout')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Payment Gateway Configuration</h1>
        <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:underline">Back to Dashboard</a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-100 border border-green-400 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <form action="{{ route('admin.payments.config.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="p-6 space-y-8">

                <!-- Enabled Gateways Selection -->
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                    <label class="block text-sm font-bold text-blue-800 mb-3">Enabled Payment Gateways</label>
                    <div class="flex flex-wrap gap-6">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="payments[enabled_gateways][]" value="paystack" {{ in_array('paystack', explode(',', $config['enabled_gateways'] ?? '')) ? 'checked' : '' }} class="w-4 h-4 text-blue-600">
                            <span class="text-gray-700 font-medium">Paystack</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="payments[enabled_gateways][]" value="flutterwave" {{ in_array('flutterwave', explode(',', $config['enabled_gateways'] ?? '')) ? 'checked' : '' }} class="w-4 h-4 text-blue-600">
                            <span class="text-gray-700 font-medium">Flutterwave</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="payments[enabled_gateways][]" value="remita" {{ in_array('remita', explode(',', $config['enabled_gateways'] ?? '')) ? 'checked' : '' }} class="w-4 h-4 text-blue-600">
                            <span class="text-gray-700 font-medium">Remita</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="payments[enabled_gateways][]" value="opay" {{ in_array('opay', explode(',', $config['enabled_gateways'] ?? '')) ? 'checked' : '' }} class="w-4 h-4 text-blue-600">
                            <span class="text-gray-700 font-medium">OPay</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="payments[enabled_gateways][]" value="quickteller" {{ in_array('quickteller', explode(',', $config['enabled_gateways'] ?? '')) ? 'checked' : '' }} class="w-4 h-4 text-blue-600">
                            <span class="text-gray-700 font-medium">Quickteller</span>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Paystack Config -->
                    <div class="p-4 border rounded-xl bg-gray-50">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                            <span class="w-2 h-6 bg-blue-600 rounded mr-2"></span> Paystack
                        </h3>
                        <div class="space-y-4">
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="w-16 h-16 rounded-lg border bg-gray-100 overflow-hidden flex items-center justify-center">
                                    @if($config['paystack_logo'])
                                        <img src="{{ asset('storage/' . $config['paystack_logo']) }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-gray-400 text-xs">No Logo</span>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Gateway Logo</label>
                                    <input type="file" name="payments[paystack_logo]" class="text-xs w-full">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Public Key</label>
                                <input type="text" name="payments[paystack_public_key]" value="{{ $config['paystack_public_key'] }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Secret Key</label>
                                <input type="password" name="payments[paystack_secret_key]" value="{{ $config['paystack_secret_key'] }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                            </div>
                        </div>
                    </div>

                    <!-- Flutterwave Config -->
                    <div class="p-4 border rounded-xl bg-gray-50">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                            <span class="w-2 h-6 bg-orange-500 rounded mr-2"></span> Flutterwave
                        </h3>
                        <div class="space-y-4">
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="w-16 h-16 rounded-lg border bg-gray-100 overflow-hidden flex items-center justify-center">
                                    @if($config['flutterwave_logo'])
                                        <img src="{{ asset('storage/' . $config['flutterwave_logo']) }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-gray-400 text-xs">No Logo</span>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Gateway Logo</label>
                                    <input type="file" name="payments[flutterwave_logo]" class="text-xs w-full">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Public Key</label>
                                <input type="text" name="payments[flutterwave_public_key]" value="{{ $config['flutterwave_public_key'] }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Secret Key</label>
                                <input type="password" name="payments[flutterwave_secret_key]" value="{{ $config['flutterwave_secret_key'] }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                            </div>
                        </div>
                    </div>

                    <!-- Remita Config -->
                    <div class="p-4 border rounded-xl bg-gray-50">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                            <span class="w-2 h-6 bg-green-600 rounded mr-2"></span> Remita
                        </h3>
                        <div class="space-y-4">
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="w-16 h-16 rounded-lg border bg-gray-100 overflow-hidden flex items-center justify-center">
                                    @if($config['remita_logo'])
                                        <img src="{{ asset('storage/' . $config['remita_logo']) }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-gray-400 text-xs">No Logo</span>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Gateway Logo</label>
                                    <input type="file" name="payments[remita_logo]" class="text-xs w-full">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Merchant ID</label>
                                <input type="text" name="payments[remita_merchant_id]" value="{{ $config['remita_merchant_id'] }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">API Key</label>
                                <input type="password" name="payments[remita_api_key]" value="{{ $config['remita_api_key'] }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                            </div>
                        </div>
                    </div>

                    <!-- OPay Config -->
                    <div class="p-4 border rounded-xl bg-gray-50">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                            <span class="w-2 h-6 bg-green-500 rounded mr-2"></span> OPay
                        </h3>
                        <div class="space-y-4">
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="w-16 h-16 rounded-lg border bg-gray-100 overflow-hidden flex items-center justify-center">
                                    @if($config['opay_logo'])
                                        <img src="{{ asset('storage/' . $config['opay_logo']) }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-gray-400 text-xs">No Logo</span>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Gateway Logo</label>
                                    <input type="file" name="payments[opay_logo]" class="text-xs w-full">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Merchant ID</label>
                                <input type="text" name="payments[opay_merchant_id]" value="{{ $config['opay_merchant_id'] }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Public Key</label>
                                <input type="text" name="payments[opay_public_key]" value="{{ $config['opay_public_key'] }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Secret Key</label>
                                <input type="password" name="payments[opay_secret_key]" value="{{ $config['opay_secret_key'] }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Base URL</label>
                                <input type="text" name="payments[opay_base_url]" value="{{ $config['opay_base_url'] }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                            </div>
                            <div class="pt-2 border-t mt-2">
                                <label class="block text-xs font-bold text-indigo-600 uppercase mb-1">Default Payment Method (Optional)</label>
                                <select name="payments[opay_pay_method]" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm bg-white">
                                    <option value="" {{ $config['opay_pay_method'] == '' ? 'selected' : '' }}>-- Show All Options (Recommended) --</option>
                                    <option value="BankTransfer" {{ $config['opay_pay_method'] == 'BankTransfer' ? 'selected' : '' }}>Bank Transfer (QR Code)</option>
                                    <option value="Card" {{ $config['opay_pay_method'] == 'Card' ? 'selected' : '' }}>Debit Card</option>
                                    <option value="OPayAccount" {{ $config['opay_pay_method'] == 'OPayAccount' ? 'selected' : '' }}>OPay Account</option>
                                </select>
                                <p class="text-[10px] text-gray-400 mt-1 italic">Leave as "Show All" to let students choose their method.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Quickteller Config -->
                    <div class="p-4 border rounded-xl bg-gray-50">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                            <span class="w-2 h-6 bg-red-600 rounded mr-2"></span> Quickteller
                        </h3>
                        <div class="space-y-4">
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="w-16 h-16 rounded-lg border bg-gray-100 overflow-hidden flex items-center justify-center">
                                    @if($config['quickteller_logo'])
                                        <img src="{{ asset('storage/' . $config['quickteller_logo']) }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-gray-400 text-xs">No Logo</span>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Gateway Logo</label>
                                    <input type="file" name="payments[quickteller_logo]" class="text-xs w-full">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Merchant Code</label>
                                <input type="text" name="payments[quickteller_merchant_code]" value="{{ $config['quickteller_merchant_code'] }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Payable Code</label>
                                <input type="text" name="payments[quickteller_payable_code]" value="{{ $config['quickteller_payable_code'] }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Client ID</label>
                                <input type="text" name="payments[quickteller_client_id]" value="{{ $config['quickteller_client_id'] }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Secret</label>
                                <input type="password" name="payments[quickteller_secret]" value="{{ $config['quickteller_secret'] }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Base URL</label>
                                <input type="text" name="payments[quickteller_base_url]" value="{{ $config['quickteller_base_url'] }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-6 border-t">
                    <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-blue-700 transition shadow-lg">
                        Update Gateway Configurations
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
