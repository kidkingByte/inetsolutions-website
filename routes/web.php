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
Route::post('/support/report-problem', [EnquiryController::class, 'storeSupport'])->name('support.report.store');

// Coverage
Route::get('/coverage', [CoverageController::class, 'index'])->name('coverage');
Route::post('/coverage/check', [CoverageController::class, 'check'])->name('coverage.check');
Route::post('/coverage/notify', [CoverageController::class, 'notify'])->name('coverage.notify');

// Get connected / enquiries
Route::get('/get-connected', [EnquiryController::class, 'getConnected'])->name('get-connected');
Route::post('/get-connected', [EnquiryController::class, 'storeConnection'])->name('get-connected.store');

// Contact
Route::get('/contact', [EnquiryController::class, 'contact'])->name('contact');
Route::post('/contact', [EnquiryController::class, 'storeContact'])->name('contact.store');

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

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Dashboard
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('packages', Admin\PackageController::class)->except('show');
    Route::resource('coverage', Admin\CoverageController::class)->except('show');
    Route::resource('enquiries', Admin\EnquiryController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::resource('posts', Admin\PostController::class)->except('show');
    Route::resource('faqs', Admin\FaqController::class)->except('show');
    Route::resource('testimonials', Admin\TestimonialController::class)->except('show');
    Route::resource('promotions', Admin\PromotionController::class)->except('show');
    Route::resource('network-status', Admin\NetworkStatusController::class)
        ->only(['index', 'store', 'update', 'destroy'])->parameters(['network-status' => 'network_status']);

    Route::get('settings', [Admin\SettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [Admin\SettingController::class, 'update'])->name('settings.update');
});

require __DIR__.'/auth.php';
