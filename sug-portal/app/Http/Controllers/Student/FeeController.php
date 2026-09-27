<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\FeeStructure;
use App\Models\Student;
use App\Services\PaymentService;
use App\Services\SettingsService;
use Illuminate\Support\Facades\Auth;

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

        // Filter out fees that have already been paid successfully by this student
        $requiredFees = $requiredFees->filter(function ($fee) use ($student) {
            // Primary check: Direct link to this fee structure
            $hasDirectPayment = Payment::where('student_id', $student->id)
                ->where('fee_structure_id', $fee->id)
                ->whereIn('status', ['success', 'successful', 'completed'])
                ->exists();

            if ($hasDirectPayment) return false;

            // Fallback check: Any successful payment of the same amount in the same session
            // This handles cases where the fee structure might have been recreated or modified
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
            'student' => $student
        ]);
    }

    /**
     * Redirect student to the configured payment gateway.
     */
    public function pay()
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();

        // Determine which fee the student is paying.
        // IMPORTANT: Only pick a fee that hasn't been paid successfully yet.
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

        try {
            // Get the active gateway from settings
            $gatewayName = $this->settingsService->get('payments.active_gateway', 'opay');

            // Initialize payment via the PaymentService
            $result = $this->paymentService->initiatePayment($student, $fee, $gatewayName);

            // Redirect student to the gateway's payment URL
            return redirect()->away($result['gateway_data']['payment_url']);

        } catch (\Exception $e) {
            return redirect()->route('student.dashboard')
                ->with('error', 'Payment failed to initialize: ' . $e->getMessage());
        }
    }

    /**
     * Re-verify a specific payment.
     */
    public function requery($reference)
    {
        $payment = Payment::where('transaction_ref', $reference)->firstOrFail();

        if (Auth::id() !== $payment->student->user_id) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $isVerified = $this->paymentService->verifyPayment($reference);

            if ($isVerified) {
                return redirect()->route('student.fees')
                    ->with('success', 'Payment successfully synchronized! Your receipt is now available.');
            }

            return redirect()->route('student.fees')
                ->with('error', 'Payment is still pending or failed. Please wait a few moments or contact support.');

        } catch (\Exception $e) {
            return redirect()->route('student.fees')
                ->with('error', 'Error while synchronizing payment: ' . $e->getMessage());
        }
    }
}
