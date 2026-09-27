<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\StudentImportController;

Route::get('/import', [StudentImportController::class, 'index'])->name('students.import.index');
Route::post('/import', [StudentImportController::class, 'store'])->name('students.import.store');
