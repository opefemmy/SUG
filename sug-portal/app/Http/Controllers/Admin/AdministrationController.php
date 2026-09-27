<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdministrationService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\SugAdministration;
use App\Models\SugOfficer;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class AdministrationController extends Controller
{
    protected $adminService;

    public function __construct(AdministrationService $adminService)
    {
        $this->adminService = $adminService;
    }

    public function index(): View
    {
        $administrations = SugAdministration::all();
        $current = $this->adminService->getCurrentAdministration();
        return view('admin.administration.index', compact('administrations', 'current'));
    }

    public function create(): View
    {
        return view('admin.administration.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'term_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:current,past',
        ]);

        $this->adminService->createAdministration($validated);

        return redirect()->route('administration.index')->with('success', 'Administration created successfully.');
    }

    public function setActive(int $id): RedirectResponse
    {
        $this->adminService->setCurrentAdministration($id);
        return redirect()->route('administration.index')->with('success', 'Active administration updated.');
    }

    public function officers(SugAdministration $administration): View
    {
        $officers = SugOfficer::where('sug_administration_id', $administration->id)->with('user')->get();
        $users = User::all();
        return view('admin.administration.officers', compact('administration', 'officers', 'users'));
    }

    public function assignOfficer(Request $request, SugAdministration $administration): RedirectResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'position' => 'required|string|max:255',
            'appointment_date' => 'required|date',
            'portfolio' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('executives', 'public');
        }

        $this->adminService->assignOfficer(
            $administration->id,
            $request->user_id,
            $request->position,
            $request->appointment_date,
            $request->portfolio,
            $imagePath
        );

        return redirect()->back()->with('success', 'Officer assigned successfully.');
    }

    public function updateOfficer(Request $request, SugOfficer $officer): RedirectResponse
    {
        $request->validate([
            'position' => 'required|string|max:255',
            'portfolio' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['position', 'portfolio']);

        if ($request->hasFile('image')) {
            if ($officer->image_path) {
                Storage::disk('public')->delete($officer->image_path);
            }
            $data['image_path'] = $request->file('image')->store('executives', 'public');
        }

        $this->adminService->updateOfficer($officer->id, $data);

        return redirect()->back()->with('success', 'Officer profile updated successfully.');
    }

    public function removeOfficer(SugOfficer $officer): RedirectResponse
    {
        if ($officer->image_path) {
            Storage::disk('public')->delete($officer->image_path);
        }
        $officer->delete();

        return redirect()->back()->with('success', 'Officer removed successfully.');
    }
}
