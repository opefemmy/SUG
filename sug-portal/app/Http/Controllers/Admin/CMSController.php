<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CMS\ContentService;
use App\Models\Setting;
use App\Models\Page;
use App\Models\News;
use App\Models\Event;
use Illuminate\Http\Request;

class CMSController extends Controller
{
    protected $contentService;

    public function __construct(ContentService $contentService)
    {
        $this->contentService = $contentService;
    }

    /**
     * Manage Home Page and General Settings
     */
    public function settingsIndex()
    {
        return view('admin.cms.settings');
    }

    public function settingsForm()
    {
        $settings = \App\Models\Setting::all()->pluck('value', 'key')->toArray();
        return view('admin.cms.settings_form', compact('settings'));
    }

    public function settingsUpdate(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
        ]);

        $this->contentService->updateSiteSettings($request->settings);

        return back()->with('success', 'Site settings updated successfully!');
    }

    /**
     * Manage Static Pages (About, Contact, etc.)
     */
    public function pagesIndex()
    {
        $pages = Page::all();
        return view('admin.cms.pages', compact('pages'));
    }

    public function pageEdit($id)
    {
        $page = Page::findOrFail($id);
        return view('admin.cms.page_edit', compact('page'));
    }

    public function pageUpdate(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $this->contentService->updatePageContent($id, $request->only('title', 'content'));

        return redirect()->route('admin.cms.pages.index')->with('success', 'Page updated successfully!');
    }

    /**
     * Manage News
     */
    public function newsIndex()
    {
        $news = News::latest()->paginate(10);
        return view('admin.cms.news', compact('news'));
    }

    public function newsCreate()
    {
        return view('admin.cms.news_form');
    }

    public function newsStore(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:news_categories,id',
        ]);

        $this->contentService->manageNews($data);

        return redirect()->route('admin.cms.news.index')->with('success', 'News posted successfully!');
    }

    public function newsEdit($id)
    {
        $news = News::findOrFail($id);
        return view('admin.cms.news_form', ['news' => $news]);
    }

    public function newsUpdate(Request $request, $id)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:news_categories,id',
        ]);

        $this->contentService->manageNews($data, $id);

        return redirect()->route('admin.cms.news.index')->with('success', 'News updated successfully!');
    }

    /**
     * Manage Events
     */
    public function eventsIndex()
    {
        $events = Event::latest()->paginate(10);
        return view('admin.cms.events', compact('events'));
    }

    public function eventCreate()
    {
        return view('admin.cms.event_form');
    }

    public function eventStore(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'location' => 'required|string',
        ]);

        $this->contentService->manageEvent($data);

        return redirect()->route('admin.cms.events.index')->with('success', 'Event created successfully!');
    }

    public function eventEdit($id)
    {
        $event = Event::findOrFail($id);
        return view('admin.cms.event_form', ['event' => $event]);
    }

    public function eventUpdate(Request $request, $id)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'location' => 'required|string',
        ]);

        $this->contentService->manageEvent($data, $id);

        return redirect()->route('admin.cms.events.index')->with('success', 'Event updated successfully!');
    }
}
