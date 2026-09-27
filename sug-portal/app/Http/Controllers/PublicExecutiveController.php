<?php

namespace App\Http\Controllers;

use App\Services\AdministrationService;
use App\Models\SugOfficer;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PublicExecutiveController extends Controller
{
    protected $adminService;

    public function __construct(AdministrationService $adminService)
    {
        $this->adminService = $adminService;
    }

    public function index(): View|RedirectResponse
    {
        $currentAdmin = $this->adminService->getCurrentAdministration();

        if (!$currentAdmin) {
            return redirect()->route('home')->with('error', 'No active administration found.');
        }

        $executives = SugOfficer::where('sug_administration_id', $currentAdmin->id)
            ->with('user')
            ->get();

        return view('public.executives', compact('executives'));
    }
}
