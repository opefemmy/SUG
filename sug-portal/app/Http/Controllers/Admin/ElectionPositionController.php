<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\ElectionPosition;
use Illuminate\Http\Request;

class ElectionPositionController extends Controller
{
    public function create(Election $election)
    {
        return view('admin.election_positions.create', compact('election'));
    }

    public function store(Request $request, Election $election)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $election->positions()->create($validated);

        return redirect()->route('admin.elections.show', $election->id)
            ->with('success', 'Position created successfully.');
    }

    public function edit(Election $election, ElectionPosition $position)
    {
        return view('admin.election_positions.edit', compact('election', 'position'));
    }

    public function update(Request $request, Election $election, ElectionPosition $position)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $position->update($validated);

        return redirect()->route('admin.elections.show', $election->id)
            ->with('success', 'Position updated successfully.');
    }

    public function destroy(Election $election, ElectionPosition $position)
    {
        $position->delete();

        return redirect()->route('admin.elections.show', $election->id)
            ->with('success', 'Position deleted successfully.');
    }
}
