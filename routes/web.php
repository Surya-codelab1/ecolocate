<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\FacilityMapController;
use App\Http\Controllers\PickupRequestController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\DeviceSearchController;
use App\Http\Controllers\Public\AwarenessController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/api/live-stats', [HomeController::class, 'liveStats'])->name('stats.live');

Route::get('/facilities', [FacilityMapController::class, 'index'])->name('facilities.map');

Route::middleware('auth')->group(function () {
    Route::get('/pickup-requests/create/{facility}', [PickupRequestController::class, 'create'])
        ->name('pickup-requests.create');
    Route::post('/pickup-requests/{facility}', [PickupRequestController::class, 'store'])
        ->name('pickup-requests.store');
});

Route::get('/contact-us', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact-us', [ContactController::class, 'store'])->name('contact.store');

Route::get('/device-search', [DeviceSearchController::class, 'index'])->name('devices.search');
Route::get('/device-search/results', [DeviceSearchController::class, 'search'])->name('devices.search.results');
Route::get('/awareness', [AwarenessController::class, 'index'])->name('awareness.index');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
require __DIR__.'/facility.php';
require __DIR__.'/admin.php';