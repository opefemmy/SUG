<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicNewsController extends Controller
{
    public function show($slug): View
    {
        $news = News::with('images')->where('slug', $slug)->where('is_published', true)->firstOrFail();

        return view('public.news-show', compact('news'));
    }
}