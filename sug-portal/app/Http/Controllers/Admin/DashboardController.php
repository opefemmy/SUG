<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_users' => \App\Models\User::count(),
            'total_revenue' => \App\Models\Payment::whereIn('status', ['success', 'successful', 'completed'])->sum('amount'),
            'total_transactions' => \App\Models\Payment::whereIn('status', ['success', 'successful', 'completed'])->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
