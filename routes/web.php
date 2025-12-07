<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImageController;

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Api\LprController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Api\AlertController as ApiAlertController;


// -------------------------------
// DEFAULT REDIRECT
// -------------------------------
Route::get('/', function () {
    return redirect()->route('login');
});


// -------------------------------
// AUTH ROUTES
// -------------------------------
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('check.invited');

// Google Auth
Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');


// -------------------------------
// INVITE REGISTRATION
// -------------------------------

Route::get('/register/invite/{token}', [RegisteredUserController::class, 'acceptInvite'])
    ->name('invite.accept');

Route::post('/register/invite/{token}', [RegisteredUserController::class, 'inviteRegister'])
    ->name('invite.register.submit');

// -------------------------------
// PUBLIC SETTINGS FETCH (logo, header)
// -------------------------------
Route::get('/settings', [SettingController::class, 'getSettings']);


// -------------------------------
// USER AUTHENTICATED ROUTES
// -------------------------------
Route::middleware(['auth'])->group(function () {

    // Dashboard UI
    Route::get('/dashboard-ui', [DashboardController::class, 'index'])->name('dashboard.ui');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ---------------------------
    // ADMIN PANEL
    // ---------------------------
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/update', [AdminController::class, 'update'])->name('admin.update');

    // Admin Invite System
    Route::get('/invite', [AdminController::class, 'invitePage'])->name('invite.page');
    Route::post('/admin/invite', [AdminController::class, 'invite'])->name('admin.invite.send');

    // Alerts
    Route::get('/alerts', [AlertController::class, 'index'])->name('alerts.index');
});


// -------------------------------
// API ROUTES (Sanctum Protected)
// -------------------------------
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/api/cameras', [LprController::class, 'cameras']);
    Route::get('/api/detections', [LprController::class, 'detections']);
    Route::get('/api/detections/{id}/download', [LprController::class, 'download']);

    Route::get('/api/live', [LprController::class, 'live']);

    Route::get('/image-proxy', [ImageController::class, 'show'])->name('image.proxy');

    // Alerts (API)
    Route::post('/alerts', [ApiAlertController::class, 'store'])->name('alerts.store');
    Route::delete('/alerts/{id}', [ApiAlertController::class, 'destroy'])->name('alerts.delete');
});


// -------------------------------
// LPR ROUTES (dashboard data fetch)
// -------------------------------
Route::get('/dashboard/detections', [LprController::class, 'detections']);
Route::get('/dashboard/live', [LprController::class, 'live']);


// Laravel built-in auth routes
require __DIR__.'/auth.php';
