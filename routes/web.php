<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\CoverageController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Website
|--------------------------------------------------------------------------
*/

Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/about', [SiteController::class, 'about'])->name('about');
Route::get('/internet', [SiteController::class, 'internet'])->name('internet');
Route::get('/solutions', [SiteController::class, 'solutions'])->name('solutions');
Route::get('/packages', [SiteController::class, 'packages'])->name('packages');
Route::get('/app', [SiteController::class, 'app'])->name('app');
Route::get('/faq', [SiteController::class, 'faq'])->name('faq');
Route::get('/speed-test', [SiteController::class, 'speedTest'])->name('speed-test');
Route::get('/network-status', [SiteController::class, 'networkStatus'])->name('network-status');

// Support
Route::get('/support', [SiteController::class, 'support'])->name('support');
Route::get('/support/report-problem', [EnquiryController::class, 'support'])->name('support.report');
Route::post('/support/report-problem', [EnquiryController::class, 'storeSupport'])->name('support.report.store')->middleware('throttle:forms');

// Coverage
Route::get('/coverage', [CoverageController::class, 'index'])->name('coverage');
Route::post('/coverage/check', [CoverageController::class, 'check'])->name('coverage.check')->middleware('throttle:coverage');
Route::post('/coverage/notify', [CoverageController::class, 'notify'])->name('coverage.notify')->middleware('throttle:forms');

// Get connected / enquiries
Route::get('/get-connected', [EnquiryController::class, 'getConnected'])->name('get-connected');
Route::post('/get-connected', [EnquiryController::class, 'storeConnection'])->name('get-connected.store')->middleware('throttle:forms');

// Contact
Route::get('/contact', [EnquiryController::class, 'contact'])->name('contact');
Route::post('/contact', [EnquiryController::class, 'storeContact'])->name('contact.store')->middleware('throttle:forms');

// News / blog
Route::get('/news', [PostController::class, 'index'])->name('news');
Route::get('/news/{post:slug}', [PostController::class, 'show'])->name('news.show');

// Legal
Route::get('/legal/{page}', [SiteController::class, 'legal'])->name('legal');

/*
|--------------------------------------------------------------------------
| Authenticated (Breeze)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Breeze's default post-login home. This project's authenticated area is the admin CMS.
    Route::get('/dashboard', fn () => redirect()->route('admin.dashboard'))->name('dashboard');
});

// Staff "My profile". No self-deletion: accounts are removed by an Administrator (Staff & roles),
// which keeps the last-admin safeguard and the audit trail intact.
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| Admin Dashboard
|--------------------------------------------------------------------------
*/

// 'admin' = any active staff role; each section then requires its own permission (config/roles.php).
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Enquiries hold both sales leads and support tickets; the controller checks leads.* / support.* per record.
    Route::get('enquiries/export', [Admin\EnquiryController::class, 'export'])->name('enquiries.export')->middleware('can:leads.export');
    Route::resource('enquiries', Admin\EnquiryController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::get('enquiries/{enquiry}/attachment', [Admin\EnquiryController::class, 'attachment'])->name('enquiries.attachment');

    Route::resource('packages', Admin\PackageController::class)->except('show')->middleware('can:packages.manage');
    Route::resource('coverage', Admin\CoverageController::class)->except('show')->middleware('can:coverage.manage');

    Route::middleware('can:content.manage')->group(function () {
        Route::resource('posts', Admin\PostController::class)->except('show');
        Route::resource('faqs', Admin\FaqController::class)->except('show');
        Route::resource('testimonials', Admin\TestimonialController::class)->except('show');
        Route::resource('promotions', Admin\PromotionController::class)->except('show');
    });

    Route::resource('network-status', Admin\NetworkStatusController::class)
        ->only(['index', 'store', 'update', 'destroy'])->parameters(['network-status' => 'network_status'])
        ->middleware('can:network.manage');

    Route::middleware('can:settings.manage')->group(function () {
        Route::get('settings', [Admin\SettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [Admin\SettingController::class, 'update'])->name('settings.update');
    });

    Route::resource('users', Admin\UserController::class)->except('show')->middleware('can:users.manage');
    Route::get('audit-log', [Admin\AuditLogController::class, 'index'])->name('audit.index')->middleware('can:audit.view');
});

require __DIR__.'/auth.php';
