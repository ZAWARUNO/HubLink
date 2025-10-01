<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CMS\HomeController as CmsHomeController;
use App\Http\Controllers\CMS\DomainController as CmsDomainController;
use App\Models\Domain;

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// CMS routes (require auth)
Route::middleware('auth')->prefix('cms')->name('cms.')->group(function () {
    Route::get('/', [CmsHomeController::class, 'index'])->name('home');
    Route::get('/domain/setup', [CmsDomainController::class, 'showSetupForm'])->name('domain.setup');
    Route::post('/domain', [CmsDomainController::class, 'store'])->name('domain.store');
});

// Public profile by slug
Route::get('/{slug}', function (string $slug) {
    $domain = Domain::where('slug', $slug)->firstOrFail();
    return view('frontend.profile', compact('domain'));
})->where('slug', '[A-Za-z0-9\-]+');
