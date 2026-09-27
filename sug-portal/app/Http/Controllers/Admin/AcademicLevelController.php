<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\Programme;
use Illuminate\Http\Request;

class AcademicLevelController extends Controller
{
    public function index()
    {
        $levels = Level::with('programme')->latest()->paginate(15);
        return view('admin.academic.levels.index', compact('levels'));
    }

    public function create()
    {
        $programmes = Programme::all();
        return view('admin.academic.levels.create', compact('programmes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'level_number' => 'required|string|max:50',
            'programme_id' => 'nullable|exists:programmes,id',
        ]);

        Level::create($validated);

        return redirect()->route('admin.academic.levels.index')->with('success', 'Academic level created successfully.');
    }

    public function edit(Level $level)
    {
        $programmes = Programme::all();
        return view('admin.academic.levels.edit', compact('level', 'programmes'));
    }

    public function update(Request $request, Level $level)
    {
        $validated = $request->validate([
            'level_number' => 'required|string|max:50',
            'programme_id' => 'nullable|exists:programmes,id',
        ]);

        $level->update($validated);

        return redirect()->route('admin.academic.levels.index')->with('success', 'Academic level updated successfully.');
    }

    public function destroy(Level $level)
    {
        $level->delete();
        return redirect()->route('admin.academic.levels.index')->with('success', 'Academic level deleted successfully.');
    }
}
