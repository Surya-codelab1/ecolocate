<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
use App\Http\Controllers\Api\FacilityApiController;

Route::get('/facilities', [FacilityApiController::class, 'index']);
Route::get('/facilities/{id}', [FacilityApiController::class, 'show']);