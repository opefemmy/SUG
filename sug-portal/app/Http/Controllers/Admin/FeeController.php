<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeeStructure;
use App\Models\FeeType;
use App\Models\Level;
use App\Models\Programme;
use App\Models\AcademicSession;
use Illuminate\Http\Request;

class FeeController extends Controller
{
    public function index()
    {
        $fees = FeeStructure::with(['feeType', 'level', 'programme', 'session'])->latest()->paginate(15);
        return view('admin.fees.index', compact('fees'));
    }

    public function create()
    {
        $feeTypes = FeeType::all();
        $levels = Level::all();
        $programmes = Programme::all();
        $sessions = AcademicSession::all();

        return view('admin.fees.create', compact('feeTypes', 'levels', 'programmes', 'sessions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fee_type_id' => 'required|exists:fee_types,id',
            'amount' => 'required|numeric|min:0',
            'level_id' => 'required|exists:levels,id',
            'programme_id' => 'nullable|exists:programmes,id',
            'session_id' => 'required|exists:academic_sessions,id',
            'is_mandatory' => 'boolean',
        ]);

        // IMPORTANT: Laravel's $request->validate() can return empty strings for nullable fields.
        // SQLite/MySQL Foreign Keys will fail if we try to insert an empty string "" into a foreign key column.
        $data = $request->all();
        $data['programme_id'] = $request->filled('programme_id') ? $request->programme_id : null;
        $data['is_mandatory'] = $request->has('is_mandatory');

        FeeStructure::create($data);

        return redirect()->route('admin.fees.index')->with('success', 'Fee structure created successfully.');
    }

    public function edit(FeeStructure $fee)
    {
        $feeTypes = FeeType::all();
        $levels = Level::all();
        $programmes = Programme::all();
        $sessions = AcademicSession::all();

        return view('admin.fees.edit', compact('fee', 'feeTypes', 'levels', 'programmes', 'sessions'));
    }

    public function update(Request $request, FeeStructure $fee)
    {
        $validated = $request->validate([
            'fee_type_id' => 'required|exists:fee_types,id',
            'amount' => 'required|numeric|min:0',
            'level_id' => 'required|exists:levels,id',
            'programme_id' => 'nullable|exists:programmes,id',
            'session_id' => 'required|exists:academic_sessions,id',
            'is_mandatory' => 'boolean',
        ]);

        $data = $request->all();
        $data['programme_id'] = $request->filled('programme_id') ? $request->programme_id : null;
        $data['is_mandatory'] = $request->has('is_mandatory');

        $fee->update($data);

        return redirect()->route('admin.fees.index')->with('success', 'Fee structure updated successfully.');
    }

    public function destroy(FeeStructure $fee)
    {
        $fee->delete();
        return redirect()->route('admin.fees.index')->with('success', 'Fee structure deleted successfully.');
    }
}
