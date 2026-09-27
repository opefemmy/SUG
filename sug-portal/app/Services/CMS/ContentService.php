<?php

namespace App\Services\CMS;

use App\Models\Setting;
use App\Models\Page;
use App\Models\News;
use App\Models\Event;
use Illuminate\Support\Facades\DB;

class ContentService
{
    public function updateSiteSettings(array $data): void
    {
        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }

    public function updatePageContent(int $pageId, array $data): void
    {
        $page = Page::findOrFail($pageId);
        $page->update($data);
    }

    public function manageNews(array $data, ?int $id = null): News
    {
        if ($id) {
            $news = News::findOrFail($id);
            $news->update($data);
            return $news;
        }
        return News::create($data);
    }

    public function manageEvent(array $data, ?int $id = null): Event
    {
        if ($id) {
            $event = Event::findOrFail($id);
            $event->update($data);
            return $event;
        }
        return Event::create($data);
    }
}
