<?php

namespace App\Services;

use App\Models\HeroSlider;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class HeroSliderService
{
    public function getActiveSlides()
    {
        return HeroSlider::orderBy('sort_order', 'asc')->get();
    }

    public function addSlide(array $data, UploadedFile $file)
    {
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('branding/sliders', $filename, 'public');

        return HeroSlider::create([
            'image_path' => $path,
            'title' => $data['title'] ?? null,
            'subtitle' => $data['subtitle'] ?? null,
            'cta_primary_text' => $data['cta_primary_text'] ?? null,
            'cta_primary_url' => $data['cta_primary_url'] ?? null,
            'cta_secondary_text' => $data['cta_secondary_text'] ?? null,
            'cta_secondary_url' => $data['cta_secondary_url'] ?? null,
            'overlay_color' => $data['overlay_color'] ?? 'rgba(0,0,0,0.4)',
            'sort_order' => DB::raw(' (SELECT COALESCE(MAX(sort_order), 0) + 1 FROM hero_sliders) '),
        ]);
    }

    public function updateSlide(int $id, array $data)
    {
        $slider = HeroSlider::findOrFail($id);

        // Map frontend field names to DB columns if necessary
        // In this implementation, we assume data keys match columns
        $slider->update($data);

        return $slider;
    }

    public function removeSlide(int $id)
    {
        $slider = HeroSlider::findOrFail($id);
        if ($slider->image_path) {
            Storage::disk('public')->delete($slider->image_path);
        }
        return $slider->delete();
    }

    public function updateOrder(array $orderMap)
    {
        foreach ($orderMap as $id => $order) {
            HeroSlider::where('id', $id)->update(['sort_order' => $order]);
        }
    }
}
