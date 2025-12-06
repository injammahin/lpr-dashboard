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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// Authentication Protected Routes
Route::middleware(['auth'])->group(function () {

    // Dashboard UI
    Route::get('/dashboard-ui', [DashboardController::class, 'index'])->name('dashboard.ui');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// API Routes for LPR System
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/api/cameras', [LprController::class, 'cameras']);
    Route::get('/api/detections', [LprController::class, 'detections']);
    Route::get('/api/detections/{id}/download', [LprController::class, 'download']);
    Route::get('/api/live', [LprController::class, 'live']);
    Route::get('/image-proxy', [ImageController::class, 'show'])->name('image.proxy');
    Route::get('/settings', [SettingController::class, 'getSettings']);

});

Route::middleware('auth')->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/update', [AdminController::class, 'update'])->name('admin.update');
});
// Authentication Scaffolding
require __DIR__.'/auth.php';
