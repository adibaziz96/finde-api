<?php

use App\Http\Controllers\AuthController;
use App\Http\Middleware\Unauthenticated;

Route::middleware([Unauthenticated::class])->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return 'test';
    });
});
