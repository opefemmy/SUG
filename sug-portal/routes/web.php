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
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Services\SettingsService;
use App\Http\Controllers\Admin\SupportTicketController;

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
    Route::get('/biodata/departments', [StudentBiodataController::class, 'getDepartments']);
    Route::get('/biodata/programmes', [StudentBiodataController::class, 'getProgrammes']);
    Route::get('/biodata/change-password', [StudentBiodataController::class, 'changePassword'])->name('auth.change_password');
    Route::post('/biodata/update-password', [StudentBiodataController::class, 'updatePassword'])->name('auth.update_password');

    // Profile
    Route::get('/profile', [StudentProfileController::class, 'index'])->name('profile');

    // Fees
    Route::get('/fees', [StudentFeeController::class, 'index'])->name('fees');
    Route::get('/fees/pay', [StudentFeeController::class, 'pay'])->name('fees.pay');
    Route::post('/fees/process', [StudentFeeController::class, 'processPayment'])->name('fees.process');
    Route::get('/fees/requery/{reference}', [StudentFeeController::class, 'requery'])->name('fees.requery');
    Route::post('/fees/verify-manual', [StudentFeeController::class, 'verifyManual'])->name('fees.verifyManual');

    // Elections
    Route::get('/elections', [StudentVotingController::class, 'index'])->name('elections.index');

    // Support
    Route::get('/support', [StudentSupportController::class, 'index'])->name('support.index');
    Route::post('/support', [StudentSupportController::class, 'store'])->name('support.store');

    // Payment Complaints
    Route::get('/payment-complaints', [\App\Http\Controllers\Student\PaymentComplaintController::class, 'index'])->name('complaints.index');
    Route::get('/payment-complaints/create', [\App\Http\Controllers\Student\PaymentComplaintController::class, 'create'])->name('complaints.create');
    Route::post('/payment-complaints', [\App\Http\Controllers\Student\PaymentComplaintController::class, 'store'])->name('complaints.store');
});

// Defining receipt.download outside the student. prefix group to match the view's route('receipt.download')
Route::get('/student/receipts/download/{id}', [StudentReceiptController::class, 'download'])->name('receipt.download');

// Receipt Verification Route
Route::get('/verify/receipt/{payment_id}', [\App\Http\Controllers\Student\ReceiptVerificationController::class, 'verify'])->name('receipt.verify');

// Impersonation / Unlock Routes
Route::get('/unlock', [ \App\Http\Controllers\Admin\ImpersonationController::class, 'showLogin'])->name('unlock.login');
Route::post('/unlock/auth', [ \App\Http\Controllers\Admin\ImpersonationController::class, 'authenticate'])->name('unlock.auth');

Route::middleware([\App\Http\Middleware\EnsureIsMasterAdmin::class])->group(function () {
    Route::get('/unlock/dashboard', [ \App\Http\Controllers\Admin\ImpersonationController::class, 'index'])->name('unlock.dashboard');
    Route::post('/unlock/impersonate', [ \App\Http\Controllers\Admin\ImpersonationController::class, 'impersonate'])->name('unlock.impersonate');
    Route::post('/unlock/stop', [ \App\Http\Controllers\Admin\ImpersonationController::class, 'stop'])->name('unlock.stop');
});

// Settings Routes
Route::prefix('admin')->middleware('permission:edit settings')->group(function () {
    Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/slider/upload', [AdminSettingController::class, 'uploadSliderImage'])->name('settings.slider.upload');
    Route::delete('/settings/slider/{id}', [AdminSettingController::class, 'destroySliderImage'])->name('settings.slider.destroy');
    Route::put('/settings/slider/{id}', [AdminSettingController::class, 'updateSliderImage'])->name('settings.slider.update');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('permission:manage users')->group(function () {
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    });

    Route::middleware('permission:manage schools')->group(function () {
        Route::resource('administration', AdministrationController::class);
        Route::post('administration/{administration}/assign_officer', [AdministrationController::class, 'assignOfficer'])->name('administration.assign_officer');
        Route::put('administration/officers/{officer}/update', [AdministrationController::class, 'updateOfficer'])->name('administration.update_officer');
        Route::delete('administration/officers/{officer}/remove_officer', [AdministrationController::class, 'remove_officer'])->name('administration.remove_officer');
    });

    Route::middleware('permission:manage news')->group(function () {
        Route::resource('news', AdminNewsController::class);
    });

    Route::prefix('academic')->name('academic.')->group(function () {
        Route::middleware('permission:manage schools')->group(function () {
            Route::resource('schools', SchoolController::class);
            Route::get('schools/template', [SchoolController::class, 'downloadTemplate'])->name('schools.template');
            Route::post('schools/import', [SchoolController::class, 'import'])->name('schools.import');
        });
        Route::middleware('permission:manage departments')->group(function () {
            Route::resource('departments', DepartmentController::class);
        });
        Route::middleware('permission:manage programmes')->group(function () {
            Route::resource('programmes', ProgrammeController::class);
            Route::get('programmes/template', [ProgrammeController::class, 'downloadTemplate'])->name('programmes.template');
            Route::post('programmes/import', [ProgrammeController::class, 'import'])->name('programmes.import');
        });
        Route::middleware('permission:manage sessions')->group(function () {
            Route::resource('sessions', \App\Http\Controllers\Admin\AcademicSessionController::class);
        });
        Route::middleware('permission:manage levels')->group(function () {
            Route::resource('levels', \App\Http\Controllers\Admin\AcademicLevelController::class);
        });
    });

    Route::middleware('permission:manage users')->group(function () {
        Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class)->names([
            'index' => 'roles.index',
            'create' => 'roles.create',
            'store' => 'roles.store',
            'edit' => 'roles.edit',
            'update' => 'roles.update',
            'destroy' => 'roles.destroy',
        ]);
    });

    Route::middleware('permission:view fees')->group(function () {
        Route::get('fees', [\App\Http\Controllers\Admin\FeeController::class, 'index'])->name('fees.index');
    });
    Route::middleware('permission:edit fees')->group(function () {
        Route::get('fees/create', [\App\Http\Controllers\Admin\FeeController::class, 'create'])->name('fees.create');
        Route::get('fees/{fee}/edit', [\App\Http\Controllers\Admin\FeeController::class, 'edit'])->name('fees.edit');
        Route::put('fees/{fee}', [\App\Http\Controllers\Admin\FeeController::class, 'update'])->name('fees.update');
        Route::delete('fees/{fee}', [\App\Http\Controllers\Admin\FeeController::class, 'destroy'])->name('fees.destroy');
    });
    Route::middleware('permission:view payments')->group(function () {
        Route::get('debtors', [\App\Http\Controllers\Admin\DebtorController::class, 'index'])->name('debtors.index');
    });
    Route::middleware('permission:view payments')->group(function () {
        Route::get('payments-config', [\App\Http\Controllers\Admin\PaymentConfigController::class, 'index'])->name('payments.config.index');
        Route::post('payments-config', [\App\Http\Controllers\Admin\PaymentConfigController::class, 'update'])->name('payments.config.update');
    });
    Route::middleware('permission:view payments')->group(function () {
        Route::get('payments-history', [\App\Http\Controllers\Admin\PaymentHistoryController::class, 'index'])->name('payments.history');
        Route::post('payments-history/{id}/mark-unpaid', [\App\Http\Controllers\Admin\PaymentHistoryController::class, 'markAsUnpaid'])->name('payments.history.mark_unpaid');
    });

    Route::middleware('permission:manage news')->group(function () {
        Route::resource('elections', ElectionController::class);
        Route::resource('candidates', CandidateController::class);
    });

    Route::middleware('permission:view students')->group(function () {
        Route::resource('students', \App\Http\Controllers\Admin\StudentController::class);
        Route::get('students/import', [\App\Http\Controllers\Admin\StudentImportController::class, 'index'])->name('students.import.index');
        Route::post('students/import', [\App\Http\Controllers\Admin\StudentImportController::class, 'import'])->name('students.import.store');
    });

    Route::prefix('promotion')->name('promotion.')->group(function () {
        Route::middleware('permission:promote students')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\PromotionController::class, 'index'])->name('index');
            Route::post('/process', [\App\Http\Controllers\Admin\PromotionController::class, 'process'])->name('process');
        });
    });

    Route::middleware('permission:manage users')->group(function () {
        Route::prefix('payment-complaints')->name('payment.complaints.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\PaymentComplaintController::class, 'index'])->name('index');
            Route::get('/{id}', [\App\Http\Controllers\Admin\PaymentComplaintController::class, 'show'])->name('show');
            Route::post('/{id}/verify', [\App\Http\Controllers\Admin\PaymentComplaintController::class, 'verify'])->name('payment.complaints.verify');
            Route::post('/{id}/reject', [\App\Http\Controllers\Admin\PaymentComplaintController::class, 'reject'])->name('payment.complaints.reject');
        });
    });

    Route::middleware('permission:manage users')->group(function () {
        Route::prefix('support')->name('support.')->group(function () {
            Route::get('/', [SupportTicketController::class, 'index'])->name('index');
            Route::get('/{id}', [\App\Http\Controllers\Admin\SupportTicketController::class, 'show'])->name('show');
            Route::post('/{id}/status', [SupportTicketController::class, 'updateStatus'])->name('update_status');
        });
    });
});

Route::get('/news', function() {
    return view('public.news', [
        'news' => \App\Models\News::with('category')->latest()->paginate(10),
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

Route::get('/news/{slug}', [PublicNewsController::class, 'show'])->name('news.show');
