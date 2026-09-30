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
            'enabled_gateways' => 'paystack',
            'paystack_public_key' => '',
            'paystack_secret_key' => '',
            'paystack_logo' => '',
            'flutterwave_public_key' => '',
            'flutterwave_secret_key' => '',
            'flutterwave_logo' => '',
            'remita_merchant_id' => '',
            'remita_api_key' => '',
            'remita_logo' => '',
            'opay_merchant_id' => '',
            'opay_public_key' => '',
            'opay_secret_key' => '',
            'opay_base_url' => 'https://testapi.opaycheckout.com/api/v1/international',
            'opay_pay_method' => '',
            'opay_logo' => '',
            'quickteller_merchant_id' => '',
            'quickteller_api_key' => '',
            'quickteller_api_secret' => '',
            'quickteller_base_url' => 'https://stg-api.quickteller.com/api/v1',
            'quickteller_logo' => '',
        ];

        $config = array_merge($defaultKeys, $config);

        return view('admin.payments.config', compact('config'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'payments.enabled_gateways' => 'required|array',
            'payments.paystack_public_key' => 'nullable|string',
            'payments.paystack_secret_key' => 'nullable|string',
            'payments.paystack_logo' => 'nullable|image|max:2048',
            'payments.flutterwave_public_key' => 'nullable|string',
            'payments.flutterwave_secret_key' => 'nullable|string',
            'payments.flutterwave_logo' => 'nullable|image|max:2048',
            'payments.remita_merchant_id' => 'nullable|string',
            'payments.remita_api_key' => 'nullable|string',
            'payments.remita_logo' => 'nullable|image|max:2048',
            'payments.opay_merchant_id' => 'nullable|string',
            'payments.opay_public_key' => 'nullable|string',
            'payments.opay_secret_key' => 'nullable|string',
            'payments.opay_base_url' => 'nullable|string',
            'payments.opay_pay_method' => 'nullable|string',
            'payments.opay_logo' => 'nullable|image|max:2048',
            'payments.quickteller_merchant_id' => 'nullable|string',
            'payments.quickteller_api_key' => 'nullable|string',
            'payments.quickteller_api_secret' => 'nullable|string',
            'payments.quickteller_base_url' => 'nullable|string',
            'payments.quickteller_logo' => 'nullable|image|max:2048',
        ]);

        foreach ($request->payments as $key => $value) {
            $saveValue = $value;

            if ($key === 'enabled_gateways' && is_array($value)) {
                $saveValue = implode(',', $value);
            } elseif ($request->hasFile("payments.{$key}")) {
                $file = $request->file("payments.{$key}");
                $filename = time() . '_' . $file->getClientOriginalName();
                $saveValue = $file->storeAs('branding/payments', $filename, 'public');
            }

            // Save with the key as is, but in the 'payments' group.
            // If we want it to be accessible as 'payments.enabled_gateways',
            // we should be consistent about whether the dot is in the key itself or handled by the service.
            $this->settingsService->set($key, $saveValue, 'payments');
        }

        return redirect()->back()->with('success', 'Payment configuration updated successfully.');
    }
}
