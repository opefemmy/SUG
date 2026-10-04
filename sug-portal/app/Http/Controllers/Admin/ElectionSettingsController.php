<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class ElectionSettingsController extends Controller
{
    public function toggleVoting(): RedirectResponse
    {
        $currentStatus = \App\Services\SettingsService::get('voting_enabled', false);
        \App\Services\SettingsService::set('voting_enabled', !$currentStatus);

        return redirect()->back()->with('success', 'Voting section has been ' . (!$currentStatus ? 'enabled' : 'disabled') . ' for students.');
    }
}
