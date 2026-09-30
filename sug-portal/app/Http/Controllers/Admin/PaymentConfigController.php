<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingsService;
use Illuminate\Http\Request;

class PaymentConfigController extends Controller
{
    protected $settingsService;

    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    public function index()
    {
        $config = $this->settingsService->getGroup('payments');

        // Define the required keys for each gateway to ensure they appear in the form
        $defaultKeys = [
            'active_gateway' => 'paystack',
            'paystack_public_key' => '',
            'paystack_secret_key' => '',
            'flutterwave_public_key' => '',
            'flutterwave_secret_key' => '',
            'remita_merchant_id' => '',
            'remita_api_key' => '',
            'opay_merchant_id' => '',
            'opay_public_key' => '',
            'opay_secret_key' => '',
            'opay_base_url' => 'https://testapi.opaycheckout.com/api/v1/international',
            'opay_pay_method' => '',
            'quickteller_merchant_id' => '',
            'quickteller_api_key' => '',
            'quickteller_api_secret' => '',
            'quickteller_base_url' => 'https://stg-api.quickteller.com/api/v1',
        ];

        $config = array_merge($defaultKeys, $config);

        return view('admin.payments.config', compact('config'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'payments.active_gateway' => 'required|in:paystack,flutterwave,remita,opay,quickteller',
            'payments.paystack_public_key' => 'nullable|string',
            'payments.paystack_secret_key' => 'nullable|string',
            'payments.flutterwave_public_key' => 'nullable|string',
            'payments.flutterwave_secret_key' => 'nullable|string',
            'payments.remita_merchant_id' => 'nullable|string',
            'payments.remita_api_key' => 'nullable|string',
            'payments.opay_merchant_id' => 'nullable|string',
            'payments.opay_public_key' => 'nullable|string',
            'payments.opay_secret_key' => 'nullable|string',
            'payments.opay_base_url' => 'nullable|string',
            'payments.opay_pay_method' => 'nullable|string',
            'payments.quickteller_merchant_id' => 'nullable|string',
            'payments.quickteller_api_key' => 'nullable|string',
            'payments.quickteller_api_secret' => 'nullable|string',
            'payments.quickteller_base_url' => 'nullable|string',
        ]);

        foreach ($request->payments as $key => $value) {
            $this->settingsService->set($key, $value, 'payments');
        }

        return redirect()->back()->with('success', 'Payment configuration updated successfully.');
    }
}
