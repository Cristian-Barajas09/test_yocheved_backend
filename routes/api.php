<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use  \App\Http\Controllers\Api\V1\{StudentController, DisponibilityController };

Route::group(['prefix' => 'v1'], function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');

    Route::apiResource('students',StudentController::class);

    Route::prefix('students')->group(function () {
        Route::apiResource('{student}/disponibility', DisponibilityController::class);
    });
});
