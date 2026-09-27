<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PaymentHistoryController;
use App\Http\Controllers\Admin\PaymentConfigController;

Route::get('/config', [PaymentConfigController::class, 'index'])->name('admin.payments.config.index');
Route::post('/config', [PaymentConfigController::class, 'update'])->name('admin.payments.config.update');
Route::get('/history', [PaymentHistoryController::class, 'index'])->name('admin.payments.history');
