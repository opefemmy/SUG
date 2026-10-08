<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PageController extends Controller
{
    public function index(): View
    {
        $pages = Page::all();
        return view('admin.pages.index', compact('pages'));
    }

    public function edit($slug): View
    {
        $page = Page::where('slug', $slug)->firstOrFail();
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, $slug): RedirectResponse
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'required|string',
        ]);

        $page = Page::where('slug', $slug)->firstOrFail();
        $page->update($request->only(['title', 'content']));

        return redirect()->route('admin.pages.index')->with('success', 'Page updated successfully.');
    }
}
