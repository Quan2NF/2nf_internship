<?php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Public
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

// Protected
Route::middleware('auth:sanctum')->group(function () {
	Route::get('/profile', [AuthController::class, 'user']);
	Route::post('/logout', [AuthController::class, 'logout']);
});


