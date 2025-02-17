<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\DashboardController;

// Homepage route
Route::get('/', [LinkController::class, 'index'])->name('home');

// Authenticated routes
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/my-links', [LinkController::class, 'userLinks'])->name('links.user');
    Route::post('/links', [LinkController::class, 'store'])->name('links.store');
    Route::delete('/links/{link}', [LinkController::class, 'destroy'])->name('links.destroy');
});

// Public routes
Route::get('/stats/{shortCode}', [LinkController::class, 'stats'])->name('links.stats');
Route::get('/{shortCode}', [LinkController::class, 'redirect'])->name('links.redirect');
Route::post('/links/{link}/verify-password', [LinkController::class, 'verifyPassword'])
    ->name('links.verify-password');
Route::post('/p/{shortCode}', [LinkController::class, 'checkPassword'])->name('links.check-password');
