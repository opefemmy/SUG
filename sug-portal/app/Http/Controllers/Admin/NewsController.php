<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\NewsImage;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index(): View
    {
        $news = News::with('category')->paginate(10);
        return view('admin.news.index', compact('news'));
    }

    public function create(): View
    {
        $categories = NewsCategory::all();
        return view('admin.news.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:news_categories,id',
            'content' => 'required|string',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|max:2048',
            'captions' => 'nullable|array',
            'captions.*' => 'nullable|string|max:255',
        ]);

        $news = News::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'category_id' => $request->category_id,
            'content' => $request->content,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('news', 'public');
                $caption = $request->captions[$index] ?? null;

                NewsImage::create([
                    'news_id' => $news->id,
                    'image_path' => $path,
                    'caption' => $caption,
                ]);
            }
        }

        return redirect()->route('admin.news.index')->with('success', 'News post created successfully.');
    }

    public function edit($id): View
    {
        $news = News::with('images')->findOrFail($id);
        $categories = NewsCategory::all();
        return view('admin.news.edit', compact('news', 'categories'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:news_categories,id',
            'content' => 'required|string',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|max:2048',
            'captions' => 'nullable|array',
            'captions.*' => 'nullable|string|max:255',
            'remove_images' => 'nullable|array',
            'remove_images.*' => 'exists:news_images,id',
        ]);

        $news = News::findOrFail($id);
        $news->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'category_id' => $request->category_id,
            'content' => $request->content,
        ]);

        // Remove images
        if ($request->has('remove_images')) {
            foreach ($request->remove_images as $imageId) {
                $image = NewsImage::findOrFail($imageId);
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }
        }

        // Add new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('news', 'public');
                $caption = $request->captions[$index] ?? null;

                NewsImage::create([
                    'news_id' => $news->id,
                    'image_path' => $path,
                    'caption' => $caption,
                ]);
            }
        }

        return redirect()->route('admin.news.index')->with('success', 'News post updated successfully.');
    }

    public function destroy($id): RedirectResponse
    {
        $news = News::findOrFail($id);

        // Delete associated images from disk
        foreach ($news->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $news->delete();

        return redirect()->route('admin.news.index')->with('success', 'News post deleted successfully.');
    }
}
