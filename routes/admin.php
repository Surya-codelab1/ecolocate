<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    DashboardController,
    FacilityRequestController,
    FacilityController,
    DeviceController,
    DeviceRequestController,
    CityController,
    ContactMessageController,
    PickupController,
    AnalyticsController
};

Route::prefix('admin')
    ->middleware(['auth', 'role:admin'])
    ->name('admin.')
    ->group(function () {

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('facility-requests', [FacilityRequestController::class, 'index'])->name('facility-requests.index');
        Route::get('facility-requests/{id}', [FacilityRequestController::class, 'show'])->name('facility-requests.show');
        Route::post('facility-requests/{id}/approve', [FacilityRequestController::class, 'approve'])->name('facility-requests.approve');
        Route::post('facility-requests/{id}/reject', [FacilityRequestController::class, 'reject'])->name('facility-requests.reject');
Route::get('facility-requests/live', [FacilityRequestController::class, 'live'])->name('facility-requests.live');
Route::get('facility-requests/{id}', [FacilityRequestController::class, 'show'])->name('facility-requests.show');
        Route::resource('facilities', FacilityController::class)->except(['create', 'store']);

        Route::resource('devices', DeviceController::class);

        Route::get('device-requests', [DeviceRequestController::class, 'index'])->name('device-requests.index');
        Route::post('device-requests/{id}/approve', [DeviceRequestController::class, 'approve'])->name('device-requests.approve');
        Route::post('device-requests/{id}/reject', [DeviceRequestController::class, 'reject'])->name('device-requests.reject');

        Route::resource('cities', CityController::class)->except(['show']);

        Route::get('messages', [ContactMessageController::class, 'index'])->name('messages.index');
        Route::post('messages/{id}/status', [ContactMessageController::class, 'updateStatus'])->name('messages.updateStatus');

        Route::get('pickups', [PickupController::class, 'index'])->name('pickups.index');

        Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    });