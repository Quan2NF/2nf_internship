<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PositionController;
use App\Http\Controllers\Api\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.reset');
});

Route::prefix('users')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [UserController::class, 'getFilteredList']);
    Route::get('{user}', [UserController::class, 'view']);
    Route::post('/', [UserController::class, 'create']);
    Route::patch('{user}', [UserController::class, 'update']);
    Route::delete('{user}', [UserController::class, 'delete']);
    Route::get('{user}/positions', [UserController::class, 'getPositions']);
    Route::post('{user}/positions', [UserController::class, 'assignPositions']);
});

Route::prefix('positions')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [PositionController::class, 'getList']);
    Route::post('/', [PositionController::class, 'create']);
    Route::patch('{position}', [PositionController::class, 'update']);
    Route::delete('{position}', [PositionController::class, 'delete']);
});
