<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarController;

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('refresh');
    Route::post('/me', [AuthController::class, 'me'])->middleware('auth:api')->name('me');

    Route::get('/cars', [CarController::class, 'index'])->middleware('auth:api')->name('cars.index');
    Route::post('/cars', [CarController::class, 'store'])->middleware('auth:api')->name('cars.store');
    Route::get('/cars/{id}', [CarController::class, 'show'])->middleware('auth:api')->name('cars.show');
});
