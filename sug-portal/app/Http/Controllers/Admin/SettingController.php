<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingsService;
use App\Services\HeroSliderService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingController extends Controller
{
    protected $settingsService;
    protected $heroSliderService;

    public function __construct(SettingsService $settingsService, HeroSliderService $heroSliderService)
    {
        $this->settingsService = $settingsService;
        $this->heroSliderService = $heroSliderService;
    }

    public function index(): View
    {
        $groups = ['general', 'branding', 'sug'];
        $settingsData = [];

        foreach ($groups as $group) {
            $settingsData[$group] = $this->settingsService->getGroup($group);
        }

        $slides = $this->heroSliderService->getActiveSlides();

        return view('admin.settings.index', compact('settingsData', 'slides'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'settings' => 'required|array',
        ]);

        foreach ($request->settings as $key => $value) {
            $group = $this->determineGroup($key);

            if ($request->hasFile("settings.{$key}")) {
                $file = $request->file("settings.{$key}");
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('branding', $filename, 'public');
                $value = $path;
            }

            $this->settingsService->set($key, $value, $group);
        }

        return redirect()->route('settings.index')->with('success', 'Settings updated successfully.');
    }

    public function uploadSliderImage(Request $request): RedirectResponse
    {
        $request->validate([
            'image' => 'required|image|max:2048',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string',
            'cta_primary_text' => 'nullable|string|max:255',
            'cta_primary_url' => 'nullable|url',
            'cta_secondary_text' => 'nullable|string|max:255',
            'cta_secondary_url' => 'nullable|url',
            'overlay_color' => 'nullable|string',
        ]);

        $this->heroSliderService->addSlide(
            $request->only(['title', 'subtitle', 'cta_primary_text', 'cta_primary_url', 'cta_secondary_text', 'cta_secondary_url', 'overlay_color']),
            $request->file('image')
        );

        return redirect()->route('settings.index')->with('success', 'Slider image uploaded successfully.');
    }

    public function destroySliderImage($id): RedirectResponse
    {
        $this->heroSliderService->removeSlide($id);
        return redirect()->route('settings.index')->with('success', 'Slider image removed successfully.');
    }

    public function updateSliderImage(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string',
            'cta_primary_text' => 'nullable|string|max:255',
            'cta_primary_url' => 'nullable|url',
            'cta_secondary_text' => 'nullable|string|max:255',
            'cta_secondary_url' => 'nullable|url',
            'overlay_color' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);

        $this->heroSliderService->updateSlide($id, $request->all());

        return redirect()->route('settings.index')->with('success', 'Slider image updated successfully.');
    }

    protected function determineGroup(string $key): string
    {
        if (str_starts_with($key, 'brand_')) return 'branding';
        if (str_starts_with($key, 'sug_')) return 'sug';
        return 'general';
    }
}
