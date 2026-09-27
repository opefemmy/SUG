<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CMSController;

Route::prefix('cms')->group(function () {
    // Site-wide Settings (Home Page Text, Slogans)
    Route::get('/settings', [CMSController::class, 'settingsIndex'])->name('cms.settings.index');
    Route::get('/settings/edit', [CMSController::class, 'settingsForm'])->name('cms.settings.form');
    Route::post('/settings', [CMSController::class, 'settingsUpdate'])->name('cms.settings.update');

    // Static Pages (About, Contact)
    Route::get('/pages', [CMSController::class, 'pagesIndex'])->name('cms.pages.index');
    Route::get('/pages/{id}/edit', [CMSController::class, 'pageEdit'])->name('cms.pages.edit');
    Route::put('/pages/{id}', [CMSController::class, 'pageUpdate'])->name('cms.pages.update');

    // News Management
    Route::get('/news', [CMSController::class, 'newsIndex'])->name('cms.news.index');
    Route::get('/news/create', [CMSController::class, 'newsCreate'])->name('cms.news.create');
    Route::post('/news', [CMSController::class, 'newsStore'])->name('cms.news.store');
    Route::get('/news/{id}/edit', [CMSController::class, 'newsEdit'])->name('cms.news.edit');
    Route::put('/news/{id}', [CMSController::class, 'newsUpdate'])->name('cms.news.update');

    // Events Management
    Route::get('/events', [CMSController::class, 'eventsIndex'])->name('cms.events.index');
    Route::get('/events/create', [CMSController::class, 'eventCreate'])->name('cms.events.create');
    Route::post('/events', [CMSController::class, 'eventStore'])->name('cms.events.store');
    Route::get('/events/{id}/edit', [CMSController::class, 'eventEdit'])->name('cms.events.edit');
    Route::put('/events/{id}', [CMSController::class, 'eventUpdate'])->name('cms.events.update');
});
