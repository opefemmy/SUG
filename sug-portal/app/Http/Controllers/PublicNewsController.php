<?php
2
3	namespace App\Http\Controllers;
4
5	use App\Models\News;
6	use Illuminate\Http\Request;
7	use Illuminate\View\View;
8
9	class PublicNewsController extends Controller
10	{
11	    public function show($slug): View
12	    {
13	        $news = News::with('images')->where('slug', $slug)->where('is_published', true)->firstOrFail();
14
15	        return view('public.news-show', compact('news'));
16	    }
17	}
18