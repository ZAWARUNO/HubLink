<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CMS\HomeController as CmsHomeController;
use App\Http\Controllers\CMS\DomainController as CmsDomainController;
use App\Http\Controllers\CMS\BuilderController as CmsBuilderController;
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

// Profile edit route (require auth)
Route::middleware('auth')->get('/profile/edit', [AuthController::class, 'showProfileEditForm'])->name('profile.edit');
Route::middleware('auth')->put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');

// CMS routes (require auth)
Route::middleware('auth')->prefix('cms')->name('cms.')->group(function () {
    Route::get('/', [CmsHomeController::class, 'index'])->name('home');
    Route::get('/domain/setup', [CmsDomainController::class, 'showSetupForm'])->name('domain.setup');
    Route::post('/domain', [CmsDomainController::class, 'store'])->name('domain.store');
    // Domain edit routes
    Route::get('/domain/edit', [CmsDomainController::class, 'showEditForm'])->name('domain.edit');
    Route::put('/domain', [CmsDomainController::class, 'update'])->name('domain.update');
    
    // Builder routes
    Route::get('/builder', [CmsBuilderController::class, 'index'])->name('builder.index');
    Route::get('/builder/{domainId}', [CmsBuilderController::class, 'show'])->name('builder.show');
    Route::post('/builder/{domainId}/component', [CmsBuilderController::class, 'storeComponent'])->name('builder.component.store');
    Route::put('/builder/{domainId}/component/{componentId}', [CmsBuilderController::class, 'updateComponent'])->name('builder.component.update');
    Route::delete('/builder/{domainId}/component/{componentId}', [CmsBuilderController::class, 'deleteComponent'])->name('builder.component.delete');
    Route::post('/builder/{domainId}/reorder', [CmsBuilderController::class, 'reorderComponents'])->name('builder.components.reorder');
    Route::post('/builder/{domainId}/publish', [CmsBuilderController::class, 'publishComponents'])->name('builder.components.publish');
});

// Public profile by slug
Route::get('/{slug}', function (string $slug) {
    $domain = Domain::where('slug', $slug)->firstOrFail();
    return view('frontend.profile', compact('domain'));
})->where('slug', '[A-Za-z0-9\-]+');