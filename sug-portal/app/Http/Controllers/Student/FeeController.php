<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\FeeStructure;
use App\Models\Student;
use App\Models\Receipt;
use App\Services\PaymentService;
use App\Services\SettingsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FeeController extends Controller
{
    protected $paymentService;
    protected $settingsService;

    public function __construct(PaymentService $paymentService, SettingsService $settingsService)
    {
        $this->paymentService = $paymentService;
        $this->settingsService = $settingsService;
    }

    /**
     * Display the student's fee payment history and total fees to be paid.
     */
    public function index()
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();

        // 1. Fetch the fees assigned to this student based on their session, level, and programme
        $requiredFees = FeeStructure::where('session_id', $student->session_id)
            ->where(function($query) use ($student) {
                $query->where('level_id', $student->current_level_id)
                      ->orWhereNull('level_id');
            })
            ->where(function($query) use ($student) {
                $query->where('programme_id', $student->programme_id)
                      ->orWhereNull('programme_id');
            })
            ->with('feeType')
            ->get();

        // We create a separate collection for the manual verification dropdown.
        // This collection includes ALL required fees, regardless of whether they have been paid,
        // because a student might be verifying a payment that hasn't registered yet.
        $verificationFees = collect($requiredFees->all());

        // Filter out fees that have already been paid successfully for the main list
        $requiredFees = $requiredFees->filter(function ($fee) use ($student) {
            $hasDirectPayment = Payment::where('student_id', $student->id)
                ->where('fee_structure_id', $fee->id)
                ->whereIn('status', ['success', 'successful', 'completed'])
                ->exists();

            if ($hasDirectPayment) return false;

            $hasEquivalentPayment = Payment::where('student_id', $student->id)
                ->where('amount', $fee->amount)
                ->whereIn('status', ['success', 'successful', 'completed'])
                ->exists();

            return !$hasEquivalentPayment;
        });

        // 2. Fetch payments already made by the student
        $payments = Payment::where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('student.fees.index', [
            'payments' => $payments,
            'requiredFees' => $requiredFees,
            'verificationFees' => $verificationFees,
            'student' => $student
        ]);
    }

    /**
     * Show the gateway selection page.
     */
    public function pay(Request $request)
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();

        $fee = FeeStructure::where('session_id', $student->session_id)
            ->where(function($query) use ($student) {
                $query->where('level_id', $student->current_level_id)
                      ->orWhereNull('level_id');
            })
            ->where(function($query) use ($student) {
                $query->where('programme_id', $student->programme_id)
                      ->orWhereNull('programme_id');
            })
            ->whereDoesntHave('payments', function($q) use ($student) {
                $q->where('student_id', $student->id)->where('status', 'success');
            })
            ->first();

        if (!$fee) {
            return redirect()->route('student.dashboard')
                ->with('error', 'No outstanding fees found for your profile.');
        }

        $enabledGatewaysString = $this->settingsService->get('enabled_gateways', 'paystack');
        if ($enabledGatewaysString === 'paystack' && !$this->settingsService->get('enabled_gateways')) {
            $enabledGatewaysString = $this->settingsService->get('payments.enabled_gateways', 'paystack');
        }

        $enabledGateways = array_filter(explode(',', $enabledGatewaysString));
        if (empty($enabledGateways)) {
            $enabledGateways = ['paystack'];
        }

        $gatewayLogos = [];
        foreach ($enabledGateways as $gateway) {
            $logoPath = $this->settingsService->get("{$gateway}_logo");
            if (!$logoPath) {
                $logoPath = $this->settingsService->get("payments.{$gateway}_logo");
            }
            $gatewayLogos[$gateway] = $logoPath;
        }

        return view('student.fees.select_gateway', [
            'enabledGateways' => $enabledGateways,
            'gatewayLogos' => $gatewayLogos,
            'fee' => $fee,
            'student' => $student
        ]);
    }

    /**
     * Process the selected gateway and initiate payment.
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'gateway' => 'required|string',
            'fee_id' => 'required|exists:fee_structures,id'
        ]);

        $student = Student::where('user_id', Auth::id())->firstOrFail();
        $fee = FeeStructure::findOrFail($request->fee_id);
        $selectedGateway = $request->gateway;

        $enabledGatewaysString = $this->settingsService->get('enabled_gateways', 'paystack');
        if ($enabledGatewaysString === 'paystack' && !$this->settingsService->get('enabled_gateways')) {
            $enabledGatewaysString = $this->settingsService->get('payments.enabled_gateways', 'paystack');
        }

        $enabledGateways = array_filter(explode(',', $enabledGatewaysString));

        if (!in_array($selectedGateway, $enabledGateways)) {
            return redirect()->route('student.fees.pay')
                ->with('error', 'The selected payment gateway is currently unavailable.');
        }

        try {
            $result = $this->paymentService->initiatePayment($student, $fee, $selectedGateway);
            return redirect()->away($result['gateway_data']['payment_url']);
        } catch (\Exception $e) {
            return redirect()->route('student.fees.pay')
                ->with('error', 'Payment failed to initialize: ' . $e->getMessage());
        }
    }

    public function requery(Request $request, $reference)
    {
        try {
            $isVerified = $this->paymentService->verifyPayment($reference);

            if ($isVerified) {
                return redirect()->route('student.fees')
                    ->with('success', 'Payment verified successfully! Your receipt is now available.');
            }

            return redirect()->route('student.fees')
                ->with('info', 'Your payment is still being processed by the gateway. Please wait a few moments and try again, or check your transaction history in your payment app.');

        } catch (\Exception $e) {
            Log::error("Payment Requery Error for {$reference}: " . $e->getMessage());
            return redirect()->route('student.fees')
                ->with('error', 'An error occurred while verifying your payment. Please try again later.');
        }
    }

    /**
     * Manually verify a payment using a transaction ID.
     */
    public function verifyManual(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|string',
            'fee_id' => 'required|exists:fee_structures,id',
            'gateway' => 'required|string',
        ]);

        $student = Student::where('user_id', Auth::id())->firstOrFail();
        $fee = FeeStructure::findOrFail($request->fee_id);

        try {
            $status = $this->paymentService->verifyGenericPayment($request->transaction_id, $request->gateway);

            if ($status === 'success') {
                DB::transaction(function () use ($student, $fee, $request) {
                    $payment = Payment::firstOrNew([
                        'transaction_ref' => $request->transaction_id
                    ]);

                    $payment->fill([
                        'student_id' => $student->id,
                        'fee_structure_id' => $fee->id,
                        'amount' => $fee->amount,
                        'payment_gateway' => $request->gateway,
                        'status' => 'success',
                        'payment_date' => now(),
                    ]);
                    $payment->save();

                    Receipt::firstOrCreate([
                        'payment_id' => $payment->id,
                    ], [
                        'receipt_no' => 'MANUAL-' . strtoupper(uniqid()),
                        'issued_at' => now(),
                    ]);
                });

                return redirect()->route('student.fees')
                    ->with('success', 'Manual verification successful! Your payment has been activated.');
            }

            return redirect()->route('student.fees')
                ->with($status === 'failed' ? 'error' : 'info',
                       $status === 'failed' ? 'The gateway reported this transaction as failed.' : 'The transaction is still pending. Please try again later.');

        } catch (\Exception $e) {
            Log::error("Manual Verification Error: " . $e->getMessage());
            return redirect()->route('student.fees')->with('error', 'An error occurred during verification.');
        }
    }

    public function callback(Request $request)
    {
        $reference = $request->query('reference') ?? $request->input('reference');
        if (!$reference) {
            return redirect()->route('student.fees')->with('error', 'Invalid payment callback: No reference provided.');
        }

        try {
            $isVerified = $this->paymentService->verifyPayment($reference);
            if ($isVerified) {
                return redirect()->route('student.fees')->with('success', 'Payment successful! Your receipt is now available.');
            }
            return redirect()->route('student.fees')->with('error', 'Payment verification failed. Please check your payment status.');
        } catch (\Exception $e) {
            Log::error("Payment Callback Error: " . $e->getMessage());
            return redirect()->route('student.fees')->with('error', 'An error occurred while verifying your payment.');
        }
    }

    public function opayWebhook(Request $request)
    {
        $payload = $request->all();
        $signature = $request->header('Opay-Signature');

        try {
            $opay = app(\App\Services\Payments\OpayGateway::class);
            $isValid = $opay->handleWebhook($payload, $signature);

            if ($isValid) {
                $reference = $payload['reference'] ?? null;
                if ($reference) {
                    $this->paymentService->verifyPayment($reference);
                    return response()->json(['status' => 'success', 'message' => 'Webhook processed'], 200);
                }
            }

            return response()->json(['status' => 'error', 'message' => 'Invalid webhook signature'], 400);
        } catch (\Exception $e) {
            Log::error("OPay Webhook Error: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Internal Server Error'], 500);
        }
    }
}
