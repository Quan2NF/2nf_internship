<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout',[AuthController::class, 'logout']);
    // List all users
    Route::get('/users', [UserController::class, 'getall']);

    // Get single user
    // Route::get('/users/{user}', [UserController::class, 'show']);

    // Update user
    Route::put('/users/{user}', [UserController::class, 'update']);

    // Delete user
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
});