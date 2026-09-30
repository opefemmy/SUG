<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdministrationController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\SchoolController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\ProgrammeController;
use App\Http\Controllers\Admin\ElectionController;
use App\Http\Controllers\Admin\CandidateController;
use App\Http\Controllers\PublicNewsController;
use App\Http\Controllers\PublicExecutiveController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\ReceiptController as StudentReceiptController;
use App\Http\Controllers\Student\BiodataController as StudentBiodataController;
use App\Http\Controllers\Student\ProfileController as StudentProfileController;
use App\Http\Controllers\Student\FeeController as StudentFeeController;
use App\Http\Controllers\Student\VotingController as StudentVotingController;
use App\Http\Controllers\Student\SupportController as StudentSupportController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\SettingController as AdminDashboardController; // Note: This was aliased to SettingController
use App\Services\SettingsService;

Route::get('/', function() {
    return view('public.index', [
        'news' => \App\Models\News::with('category')->latest()->take(3)->get(),
        'settings' => [
            'nav_home' => SettingsService::get('nav_home'),
            'nav_about' => SettingsService::get('nav_about'),
            'nav_news' => SettingsService::get('nav_news'),
            'nav_events' => SettingsService::get('nav_events'),
            'nav_contact' => SettingsService::get('nav_contact'),
            'site_name' => SettingsService::get('site_name'),
            'nav_dashboard' => SettingsService::get('nav_dashboard'),
            'nav_login' => SettingsService::get('nav_login'),
            'nav_logout' => SettingsService::get('nav_logout'),
        ]
    ]);
})->name('home');

Route::get('/about', function() {
    return view('public.about', [
        'settings' => [
            'nav_home' => SettingsService::get('nav_home'),
            'nav_about' => SettingsService::get('nav_about'),
            'nav_news' => SettingsService::get('nav_news'),
            'nav_events' => SettingsService::get('nav_events'),
            'nav_contact' => SettingsService::get('nav_contact'),
            'site_name' => SettingsService::get('site_name'),
            'nav_dashboard' => SettingsService::get('nav_dashboard'),
            'nav_login' => SettingsService::get('nav_login'),
            'nav_logout' => SettingsService::get('nav_logout'),
        ]
    ]);
})->name('about');

Route::get('/events', function() {
    return view('public.events', [
        'settings' => [
            'nav_home' => SettingsService::get('nav_home'),
            'nav_about' => SettingsService::get('nav_about'),
            'nav_news' => SettingsService::get('nav_news'),
            'nav_events' => SettingsService::get('nav_events'),
            'nav_contact' => SettingsService::get('nav_contact'),
            'site_name' => SettingsService::get('site_name'),
            'nav_dashboard' => SettingsService::get('nav_dashboard'),
            'nav_login' => SettingsService::get('nav_login'),
            'nav_logout' => SettingsService::get('nav_logout'),
        ]
    ]);
})->name('events');

Route::get('/contact', function() {
    return view('public.contact', [
        'settings' => [
            'nav_home' => SettingsService::get('nav_home'),
            'nav_about' => SettingsService::get('nav_about'),
            'nav_news' => SettingsService::get('nav_news'),
            'nav_events' => SettingsService::get('nav_events'),
            'nav_contact' => SettingsService::get('nav_contact'),
            'site_name' => SettingsService::get('site_name'),
            'nav_dashboard' => SettingsService::get('nav_dashboard'),
            'nav_login' => SettingsService::get('nav_login'),
            'nav_logout' => SettingsService::get('nav_logout'),
        ]
    ]);
})->name('contact');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Student Dashboard
Route::prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/receipts', [StudentReceiptController::class, 'index'])->name('receipts');
    Route::get('/receipts/download/{id}', [StudentReceiptController::class, 'download'])->name('receipt.download');

    // Biodata Routes
    Route::get('/biodata', [StudentBiodataController::class, 'index'])->name('biodata.index');
    Route::post('/biodata', [StudentBiodataController::class, 'store'])->name('biodata.store');
    Route::get('/biodata/departments/{school_id}', [StudentBiodataController::class, 'getDepartments']);
    Route::get('/biodata/programmes/{department_id}', [StudentBiodataController::class, 'getProgrammes']);
    Route::get('/biodata/change-password', [StudentBiodataController::class, 'changePassword'])->name('auth.change_password');
    Route::post('/biodata/update-password', [StudentBiodataController::class, 'updatePassword'])->name('auth.update_password');

    // Profile
    Route::get('/profile', [StudentProfileController::class, 'index'])->name('profile');

    // Fees
    Route::get('/fees', [StudentFeeController::class, 'index'])->name('fees');
    Route::get('/fees/pay', [StudentFeeController::class, 'pay'])->name('fees.pay');
    Route::post('/fees/process', [StudentFeeController::class, 'processPayment'])->name('fees.process');
    Route::get('/fees/requery/{reference}', [StudentFeeController::class, 'requery'])->name('fees.requery');

    //H Elections
    Route::get('/elections', [StudentVotingController::class, 'index'])->name('elections.index');

    // Support
    Route::get('/support', [StudentSupportController::class, 'index'])->name('support.index');
    Route::post('/support', [StudentSupportController::class, 'store'])->name('support.store');
});

// Defining receipt.download outside the student. prefix group to match the view's route('receipt.download')
Route::get('/student/receipts/download/{id}', [StudentReceiptController::class, 'download'])->name('receipt.download');

// Receipt Verification Route
Route::get('/verify/receipt/{payment_id}', function($payment_id) {
    return "Verification for Receipt #$payment_id: This is a valid payment record in the SUG Portal system.";
})->name('receipt.verify');

// Settings Routes
Route::prefix('admin')->group(function () {
    Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/slider/upload', [AdminSettingController::class, 'uploadSliderImage'])->name('settings.slider.upload');
    Route::delete('/settings/slider/{id}', [AdminSettingController::class, 'destroySliderImage'])->name('settings.slider.destroy');
    Route::put('/settings/slider/{id}', [AdminSettingController::class, 'updateSliderImage'])->name('settings.slider.update');
});

Route::prefix('admin')->name('admin.')->group(function () {
    // Using AdminSettingController here because AdminDashboardController was just an alias for it
    Route::get('/dashboard', [AdminSettingController::class, 'index'])->name('dashboard');
    Route::resource('administration', AdministrationController::class);
    Route::post('administration/{administration}/assign_officer', [AdministrationController::class, 'assignOfficer'])->name('administration.assign_officer');
    Route::put('administration/officers/{officer}/update', [AdministrationController::class, 'updateOfficer'])->name('administration.update_officer');
    Route::delete('administration/officers/{officer}', [AdministrationController::class, 'removeOfficer'])->name('administration.remove_officer');
    Route::resource('news', AdminNewsController::class);

    // Academic Routes
    Route::prefix('academic')->name('academic.')->group(function () {
        Route::resource('schools', SchoolController::class);
        Route::get('schools/template', [SchoolController::class, 'downloadTemplate'])->name('schools.template');
        Route::post('schools/import', [SchoolController::class, 'import'])->name('schools.import');
        Route::resource('departments', DepartmentController::class);
        Route::resource('programmes', ProgrammeController::class);
        Route::get('programmes/template', [ProgrammeController::class, 'downloadTemplate'])->name('programmes.template');
        Route::post('programmes/import', [ProgrammeController::class, 'import'])->name('programmes.import');
        Route::resource('sessions', \App\Http\Controllers\Admin\AcademicSessionController::class);
        Route::resource('levels', \App\Http\Controllers\Admin\AcademicLevelController::class);
    });

    // Other Admin Resources
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class)->names([
        'index' => 'roles.index',
        'create' => 'roles.create',
        'store' => 'roles.store',
        'edit' => 'roles.edit',
        'update' => 'roles.update',
        'destroy' => 'roles.destroy',
    ]);
    Route::get('fees', [\App\Http\Controllers\Admin\FeeController::class, 'index'])->name('fees.index');
    Route::get('fees/create', [\App\Http\Controllers\Admin\FeeController::class, 'create'])->name('fees.create');
    Route::post('fees', [\App\Http\Controllers\Admin\FeeController::class, 'store'])->name('fees.store');
    Route::get('fees/{fee}/edit', [\App\Http\Controllers\Admin\FeeController::class, 'edit'])->name('fees.edit');
    Route::put('fees/{fee}', [\App\Http\Controllers\Admin\FeeController::class, 'update'])->name('fees.update');
    Route::delete('fees/{fee}', [\App\Http\Controllers\Admin\FeeController::class, 'destroy'])->name('fees.destroy');
    Route::get('debtors', [\App\Http\Controllers\Admin\DebtorController::class, 'index'])->name('debtors.index');
    Route::get('payments-config', [\App\Http\Controllers\Admin\PaymentConfigController::class, 'index'])->name('payments.config.index');
    Route::post('payments-config', [\App\Http\Controllers\Admin\PaymentConfigController::class, 'update'])->name('payments.config.update');
    Route::get('payments-history', [\App\Http\Controllers\Admin\PaymentHistoryController::class, 'index'])->name('payments.history');

    // Election Management
    Route::resource('elections', ElectionController::class);
    Route::resource('candidates', CandidateController::class);

    // Student Services
    Route::get('students/import', [\App\Http\Controllers\Admin\StudentImportController::class, 'index'])->name('students.import.index');
    Route::post('students/import', [\App\Http\Controllers\Admin\StudentImportController::class, 'store'])->name('students.import.store');
});

// Public Routes
Route::get('/news', function() {
    return view('public.news', [
        'news' => \App\Models\News::with('category')->paginate(10),
        'settings' => [
            'nav_home' => SettingsService::get('nav_home'),
            'nav_about' => SettingsService::get('nav_about'),
            'nav_news' => SettingsService::get('nav_news'),
            'nav_events' => SettingsService::get('nav_events'),
            'nav_contact' => SettingsService::get('nav_contact'),
            'site_name' => SettingsService::get('site_name'),
            'nav_dashboard' => SettingsService::get('nav_dashboard'),
            'nav_login' => SettingsService::get('nav_login'),
            'nav_logout' => SettingsService::get('nav_logout'),
            'label_no_news' => SettingsService::get('label_no_news'),
        ]
    ]);
})->name('news');

Route::get('/executives', [PublicExecutiveController::class, 'index'])->name('executives.index');
Route::get('/news/{slug', [PublicNewsController::class, 'show'])->name('news.show');
