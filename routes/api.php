<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('providers.working-hours', \App\Http\Controllers\ProviderWorkingHoursController::class);
Route::post('providers/{provider}/working-hours/bulk', [\App\Http\Controllers\ProviderWorkingHoursController::class, 'bulkStore']);
    Route::get('providers/{provider}/availability', [\App\Http\Controllers\Api\AvailabilityController::class, 'providerAvailability']);
});

Route::apiResource('availability', \App\Http\Controllers\Api\AvailabilityController::class);
