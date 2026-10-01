<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AcademicSession;
use App\Services\StudentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PromotionController extends Controller
{
    protected $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    /**
     * Display the bulk promotion form.
     */
    public function index(): View
    {
        $sessions = AcademicSession::orderBy('session_name', 'desc')->get();
        return view('admin.promotion.index', compact('sessions'));
    }

    /**
     * Process the bulk promotion.
     */
    public function process(Request $request): RedirectResponse
    {
        $request->validate([
            'source_session_id' => 'required|exists:academic_sessions,id',
            'destination_session_id' => 'required|exists:academic_sessions,id|different:source_session_id',
        ]);

        try {
            $result = $this->studentService->bulkPromoteStudents(
                (int)$request->source_session_id,
                (int)$request->destination_session_id
            );

            return redirect()->route('admin.promotion.index')
                ->with('success', "Bulk promotion completed. Successfully promoted {$result['promoted']} students.");
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'An error occurred during bulk promotion: ' . $e->getMessage());
        }
    }
}
