<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;

Route::get('/', function () {
    return view('welcome');
});

// Authentication routes (public)
Route::middleware('guest')->group(function () {
    // Route::get('/login', function () { return view('login');})->name('login');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Protected routes (require authentication)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Document routes
    Route::prefix('documents')->group(function () {
        Route::get('/upload', [DocumentController::class, 'showUploadForm'])->name('documents.upload.form');
        Route::post('/upload', [DocumentController::class, 'upload'])->name('documents.upload');
        Route::get('/list', [DocumentController::class, 'list'])->name('documents.list');
        Route::get('/download/{path}', [DocumentController::class, 'download'])->name('documents.download');
    });
});