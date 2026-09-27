@extends('admin.layout')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">System Settings</h1>
        <p class="text-gray-600">Configure global application and branding settings.</p>
    </div>

    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="space-y-8">
            @foreach(['general' => 'General Settings', 'branding' => 'Branding & Appearance', 'sug' => 'SUG Configuration'] as $groupKey => $groupLabel)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-700">{{ $groupLabel }}</h2>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        @php
                            $groupSettings = $settingsData[$groupKey] ?? [];
                            $defaultKeys = [
                                'general' => [
                                    'site_name' => ['label' => 'Site Name', 'placeholder' => 'e.g. University of Lagos SUG'],
                                    'contact_email' => ['label' => 'Contact Email', 'placeholder' => 'support@sug.edu.ng'],
                                    'contact_phone' => ['label' => 'Contact Phone', 'placeholder' => '08012345678'],
                                    'site_background' => ['label' => 'Site Background Color', 'placeholder' => '#f3f4f6'],
                                    'site_bg_image' => ['label' => 'Login Background Image', 'placeholder' => 'Select background image...']
                                ],
                                'branding' => [
                                    'brand_primary_color' => ['label' => 'Primary Color', 'placeholder' => '#1e293b'],
                                    'brand_logo' => ['label' => 'Logo Upload', 'placeholder' => 'Select logo image...'],
                                    'brand_favicon' => ['label' => 'Favicon URL', 'placeholder' => 'https://site.com/favicon.ico']
                                ],
                                'sug' => [
                                    'sug_motto' => ['label' => 'SUG Motto', 'placeholder' => 'Service and Integrity'],
                                    'sug_vision' => ['label' => 'SUG Vision', 'placeholder' => 'To be the leading student union...']
                                ],
                            ];

                            $keys = [];
                            foreach ($defaultKeys[$groupKey] as $k => $meta) {
                                $keys[$k] = $meta['label'];
                            }
                            foreach ($groupSettings as $k => $v) {
                                if (!isset($keys[$k])) {
                                    $keys[$k] = ucwords(str_replace('_', ' ', $k));
                                }
                            }
                        @endphp
                        @foreach($keys as $key => $label)
                            <div class="flex flex-col">
                                <label class="text-sm font-medium text-gray-600 mb-1">{{ $label }}</label>
                                <div class="flex gap-2">
                                    @if($key === 'brand_primary_color' || $key === 'site_background')
                                        <input type="color" name="settings[{{ $key }}]" value="{{ $groupSettings[$key] ?? '#3b82f6' }}" class="h-10 w-12 p-1 border rounded-lg cursor-pointer">
                                    @elseif($key === 'brand_logo' || $key === 'site_bg_image')
                                        <input type="file" name="settings[{{ $key }}]" class="flex-1 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm text-gray-500 file:mr-4 file:py-1 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    @else
                                        <input type="text" name="settings[{{ $key }}]" value="{{ $groupSettings[$key] ?? '' }}" placeholder="{{ $defaultKeys[$groupKey][$key]['placeholder'] ?? '' }}" class="flex-1 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-8 flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-medium">
                Save All Settings
            </button>
        </div>
    </form>

    <div class="mt-12">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Hero Slider Management</h2>

        <!-- Add Slide Form -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mb-8">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Add New Slide</h3>
            <form action="{{ route('settings.slider.upload') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @csrf
                <div class="flex flex-col">
                    <label class="text-sm font-medium text-gray-600 mb-1">Slide Image</label>
                    <input type="file" name="image" required class="px-3 py-2 border rounded-lg text-sm text-gray-500 file:mr-4 file:py-1 file:px-4 file:rounded-full file:border-0 file:bg-blue-50 file:text-blue-700">
                </div>
                <div class="flex flex-col">
                    <label class="text-sm font-medium text-gray-600 mb-1">Title</label>
                    <input type="text" name="title" placeholder="Main headline..." class="px-3 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex flex-col">
                    <label class="text-sm font-medium text-gray-600 mb-1">Subtitle</label>
                    <input type="text" name="subtitle" placeholder="Brief description..." class="px-3 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex flex-col">
                    <label class="text-sm font-medium text-gray-600 mb-1">Primary CTA Text</label>
                    <input type="text" name="cta_primary_text" placeholder="e.g. Join Now" class="px-3 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex flex-col">
                    <label class="text-sm font-medium text-gray-600 mb-1">Primary CTA URL</label>
                    <input type="text" name="cta_primary_url" placeholder="e.g. /register" class="px-3 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex flex-col">
                    <label class="text-sm font-medium text-gray-600 mb-1">Secondary CTA Text</label>
                    <input type="text" name="cta_secondary_text" placeholder="e.g. Learn More" class="px-3 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex flex-col">
                    <label class="text-sm font-medium text-gray-600 mb-1">Secondary CTA URL</label>
                    <input type="text" name="cta_secondary_url" placeholder="e.g. /about" class="px-3 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex flex-col">
                    <label class="text-sm font-medium text-gray-600 mb-1">Overlay Color (RGBA)</label>
                    <input type="text" name="overlay_color" placeholder="rgba(0,0,0,0.4)" class="px-3 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-medium">
                        Add Slide
                    </button>
                </div>
            </form>
        </div>

        <!-- Slides List -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($slides as $slide)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex flex-col">
                    <div class="relative h-40 bg-gray-200">
                        <img src="{{ asset('storage/' . $slide->image_path) }}" class="w-full h-full object-cover">
                        <div class="absolute top-2 right-2 flex gap-2">
                            <form action="{{ route('settings.slider.destroy', $slide->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 bg-red-500 text-white rounded-lg hover:bg-red-600 transition shadow-sm">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="p-4 space-y-3">
                        <div class="flex flex-col">
                            <label class="text-xs font-semibold text-gray-500 uppercase">Title</label>
                            <input type="text" value="{{ $slide->title }}" class="w-full px-2 py-1 text-sm border rounded mt-1" onchange="updateSlide({{ $slide->id }}, 'title', this.value)">
                        </div>
                        <div class="flex flex-col">
                            <label class="text-xs font-semibold text-gray-500 uppercase">Subtitle</label>
                            <input type="text" value="{{ $slide->subtitle }}" class="w-full px-2 py-1 text-sm border rounded mt-1" onchange="updateSlide({{ $slide->id }}, 'subtitle', this.value)">
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="flex flex-col">
                                <label class="text-xs font-semibold text-gray-500 uppercase">Primary CTA</label>
                                <input type="text" value="{{ $slide->cta_primary_text }}" class="w-full px-2 py-1 text-sm border rounded mt-1" onchange="updateSlide({{ $slide->id }}, 'cta_primary_text', this.value)">
                            </div>
                            <div class="flex flex-col">
                                <label class="text-xs font-semibold text-gray-500 uppercase">Primary URL</label>
                                <input type="text" value="{{ $slide->cta_primary_url }}" class="w-full px-2 py-1 text-sm border rounded mt-1" onchange="updateSlide({{ $slide->id }}, 'cta_primary_url', this.value)">
                            </div>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t">
                            <div class="flex items-center gap-2">
                                <label class="text-xs font-semibold text-gray-500 uppercase">Overlay</label>
                                <input type="text" value="{{ $slide->overlay_color }}" class="px-2 py-1 text-xs border rounded" onchange="updateSlide({{ $slide->id }}, 'overlay_color', this.value)">
                            </div>
                            <span class="text-xs text-gray-400">Order: {{ $slide->sort_order }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        function updateSlide(id, field, value) {
            fetch(`/admin/settings/slider/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    [field]: value
                })
            }).then(response => {
                if (!response.ok) {
                    alert('Failed to update ' + field);
                }
            });
        }
    </script>
@endsection
