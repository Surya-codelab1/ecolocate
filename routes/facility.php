<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Facility\{
    DashboardController,
    FacilityProfileController,
    PickupController
};

Route::prefix('facility')
    ->middleware(['auth', 'verified', 'role:facility'])
    ->name('facility.')
    ->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('create', [FacilityProfileController::class, 'create'])->name('create');
        Route::post('create', [FacilityProfileController::class, 'store'])->name('store');

        Route::get('profile/edit', [FacilityProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [FacilityProfileController::class, 'update'])->name('profile.update');

        Route::get('pickups', [PickupController::class, 'index'])->name('pickups.index');
        Route::post('pickups/{id}/status', [PickupController::class, 'updateStatus'])->name('pickups.updateStatus');

        Route::post('pickups/{id}/generate-certificate', [PickupController::class, 'generateCertificate'])
            ->name('pickups.generateCertificate');
    });