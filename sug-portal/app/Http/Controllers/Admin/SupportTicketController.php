<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupportTicket;

class SupportTicketController extends Controller
{
    /**
     * Display a listing of general support tickets.
     */
    public function index()
    {
        $tickets = SupportTicket::with(['student', 'category'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.support.index', compact('tickets'));
    }

    /**
     * Show a specific ticket.
     */
    public function show($id)
    {
        $ticket = SupportTicket::with(['student', 'category'])->findOrFail($id);
        return view('admin.support.show', compact('ticket'));
    }

    /**
     * Update ticket status.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:open,resolved,closed',
        ]);

        $ticket = SupportTicket::findOrFail($id);
        $ticket->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Ticket status updated successfully.');
    }
}
