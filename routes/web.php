<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\LinkController;

// Homepage route
Route::get('/', [LinkController::class, 'index'])->name('home');

// Dashboard route (authenticated users)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

// URL Shortener routes (keep these last to prevent conflicts)
Route::post('/links', [LinkController::class, 'store'])->name('links.store');
Route::get('/{shortCode}', [LinkController::class, 'redirect'])->name('links.redirect');
