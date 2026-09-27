<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Models\SupportCategory;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;

class SupportController extends Controller
{
    /**
     * Show the complaints form and the list of existing tickets.
     */
    public function index()
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();
        $categories = SupportCategory::all();
        $tickets = SupportTicket::where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('student.support.index', compact('categories', 'tickets'));
    }

    /**
     * Store a new complaint ticket.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:support_categories,id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $student = Student::where('user_id', Auth::id())->firstOrFail();

        SupportTicket::create([
            'student_id' => $student->id,
            'category_id' => $request->category_id,
            'subject' => $request->subject,
            'description' => $request->description,
            'ticket_number' => 'TIC-' . strtoupper(uniqid()),
            'status' => 'open',
        ]);

        return redirect()->back()->with('success', 'Your complaint has been submitted successfully. Your ticket number is tracked in your history.');
    }
}
