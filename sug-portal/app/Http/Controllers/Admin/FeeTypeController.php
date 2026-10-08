<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeeType;
use Illuminate\Http\Request;

class FeeTypeController extends Controller
{
    public function index()
    {
        $feeTypes = FeeType::all();
        return view('admin.fee_types.index', compact('feeTypes'));
    }

    public function create()
    {
        return view('admin.fee_types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:fee_types,name',
            'description' => 'nullable|string',
        ]);

        FeeType::create($validated);

        return redirect()->route('admin.fee-types.index')
            ->with('success', 'Fee type created successfully.');
    }

    public function edit(FeeType $feeType)
    {
        return view('admin.fee_types.edit', compact('feeType'));
    }

    public function update(Request $request, FeeType $feeType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:fee_types,name,' . $feeType->id,
            'description' => 'nullable|string',
        ]);

        $feeType->update($validated);

        return redirect()->route('admin.fee-types.index')
            ->with('success', 'Fee type updated successfully.');
    }

    public function destroy(FeeType $feeType)
    {
        if ($feeType->structures()->exists()) {
            return back()->with('error', 'Cannot delete fee type with existing fee structures.');
        }

        $feeType->delete();

        return redirect()->route('admin.fee-types.index')
            ->with('success', 'Fee type deleted successfully.');
    }
}
