<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ElectionController;
use App\Http\Controllers\Admin\CandidateController;
use App\Http\Controllers\Admin\ElectionPositionController;

Route::prefix('elections')->group(function () {
    // Admin Election Management
    Route::resource('admin/elections', ElectionController::class)->names([
        'index' => 'admin.elections.index',
        'create' => 'admin.elections.create',
        'store' => 'admin.elections.store',
        'show' => 'admin.elections.show',
        'edit' => 'admin.elections.edit',
        'update' => 'admin.elections.update',
        'destroy' => 'admin.elections.destroy',
    ]);

    // Admin Candidate Management
    Route::resource('candidates', CandidateController::class)->names([
        'index' => 'admin.candidates.index',
        'create' => 'admin.candidates.create',
        'store' => 'admin.candidates.store',
        'edit' => 'admin.candidates.edit',
        'update' => 'admin.candidates.update',
        'destroy' => 'admin.candidates.destroy',
    ]);
    Route::patch('candidates/{candidate}/approve', [CandidateController::class, 'approve'])->name('admin.candidates.approve');
    Route::patch('candidates/{candidate}/reject', [CandidateController::class, 'reject'])->name('admin.candidates.reject');

    // Admin Position Management
    Route::get('elections/{election}/positions/create', [ElectionPositionController::class, 'create'])->name('admin.election-positions.create');
    Route::post('elections/{election}/positions', [ElectionPositionController::class, 'store'])->name('admin.election-positions.store');
    Route::get('elections/{election}/positions/{position}/edit', [ElectionPositionController::class, 'edit'])->name('admin.election-positions.edit');
    Route::put('elections/{election}/positions/{position}', [ElectionPositionController::class, 'update'])->name('admin.election-positions.update');
    Route::delete('elections/{election}/positions/{position}', [ElectionPositionController::class, 'destroy'])->name('admin.election-positions.destroy');

    // Student Voting Routes
    Route::get('/', [App\Http\Controllers\Student\VotingController::class, 'index'])->name('student.elections.index');
    Route::get('/vote/{election}', [App\Http\Controllers\Student\VotingController::class, 'show'])
        ->name('student.elections.vote');

    Route::post('/vote/{election}', [App\Http\Controllers\Student\VotingController::class, 'store'])
        ->name('student.elections.vote.store');

    // Admin Results Routes
    Route::get('/results/{election}', [App\Http\Controllers\Admin\ElectionResultsController::class, 'show'])
        ->name('admin.elections.results');

    Route::post('/results/{election}/publish', [App\Http\Controllers\Admin\ElectionResultsController::class, 'publish'])
        ->name('admin.elections.results.publish');
});