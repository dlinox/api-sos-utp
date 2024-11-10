<?php

use App\Http\Controllers\AlertController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'auth'], function () {
    Route::post('/sign-in', [AuthController::class, 'signIn']);
    Route::post('/sign-up', [AuthController::class, 'signUp']);
    Route::post('/sign-out', [AuthController::class, 'signOut'])->middleware('auth:sanctum');
    Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');
});

// sendAlert
Route::post('/send-alert', [AlertController::class, 'sendAlert'])->middleware('auth:sanctum');

