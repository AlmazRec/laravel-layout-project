<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')
    ->group(function () {
        Route::post('/register', [App\Http\Controllers\Api\V1\AuthController::class, 'register']);
        Route::post('/login', [App\Http\Controllers\Api\V1\AuthController::class, 'login']);
        Route::post('/logout', [App\Http\Controllers\Api\V1\AuthController::class, 'logout'])
            ->middleware('auth:sanctum');

        Route::get('/protected-route', function () {
           return 'Success!';
        })->middleware('auth:sanctum');
    });
