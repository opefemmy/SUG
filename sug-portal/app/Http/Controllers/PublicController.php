<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use App\Models\Event;
use App\Models\Page;
use App\Models\Setting;

class PublicController extends Controller
{
    public function index()
    {
        $news = News::latest()->take(3)->get();
        $events = Event::latest()->take(3)->get();
        $settings = Setting::all()->pluck('value', 'key')->toArray();

        return view('public.index', compact('news', 'events', 'settings'));
    }

    public function about()
    {
        $page = Page::where('slug', 'about')->firstOrFail();
        return view('public.about', compact('page'));
    }

    public function news()
    {
        $news = News::latest()->paginate(10);
        return view('public.news', compact('news'));
    }

    public function events()
    {
        $events = Event::latest()->paginate(10);
        return view('public.events', compact('events'));
    }

    public function contact()
    {
        $page = Page::where('slug', 'contact')->firstOrFail();
        return view('public.contact', compact('page'));
    }
}
