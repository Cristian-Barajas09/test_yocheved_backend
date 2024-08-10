<?php

use App\Http\Controllers\Api\V1\{
    DisponibilityController,
    MSDocxFilesController,
    ReportController,
    StudentController,
    UserController,
    SessionsController,
    TemplateController
};

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:api')->prefix('v1')->group(function () {
    // Route::get('/user', function (Request $request) {
    //     return $request->user();
    // })->middleware('auth:sanctum');

    Route::apiResource('students', StudentController::class);

    Route::prefix('students')->group(function () {
        Route::apiResource('{student}/availability', DisponibilityController::class);
        Route::post('{student}/upload/msdocx', [StudentController::class, 'saveSessionInformationFromDocx']);
    });

    Route::prefix('upload/msdocx')->group(function () {
        Route::post('/', [MSDocxFilesController::class, 'store']);
    });
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('sessions', SessionsController::class);


    /** OAuth */
    Route::group(['prefix' => 'oauth'], function () {
        Route::get('/logout', [UserController::class, 'logout'])->name('logout');
    });

    Route::group(['prefix' => 'reports'], function() {
        Route::get('test-pdf', [ReportController::class, 'testPdf']);
        Route::post('template/{templateId}', [ReportController::class, 'getReportByTemplate']);
    });

    Route::group(['prefix' => 'template'], function() {
        Route::post('/', [TemplateController::class, 'store']);
    });
});

Route::group(['prefix' => 'v1/oauth'], function () {
    Route::post('/login', [UserController::class, 'login'])->name('login');

    Route::get('/unauthorized', function () {
        return response()->json(['error' => 'Unauthorized'], 401);
    })->name('unauthorized');
});
