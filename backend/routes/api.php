<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\IssueController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\SettingController;
use Illuminate\Support\Facades\Route;

// Auth Routes (Public)
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']); // API01
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']); // API02
    Route::post('reset-password', [AuthController::class, 'resetPassword']); // API03

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']); // API04
        Route::get('me', [AuthController::class, 'me']);
    });
});

Route::middleware('auth:sanctum')->group(function () {
    // User Management Routes (API05-API11)
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']); // API05
        Route::get('filter', [UserController::class, 'filter']); // API06
        Route::post('/', [UserController::class, 'store']); // API07 - Admin only
        Route::put('{id}', [UserController::class, 'update']); // API08
        Route::delete('{id}', [UserController::class, 'destroy']); // API09 - Admin only
        Route::post('{id}/assign-role', [UserController::class, 'assignRole']); // API10 - Admin only
        Route::get('{id}/roles', [UserController::class, 'getUserRoles']); // API11
    })->middleware('can:manage users');

    // Role Management Routes (API12-API15)
    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'index']); // API12
        Route::post('/', [RoleController::class, 'store']); // API13
        Route::put('{id}', [RoleController::class, 'update']); // API14
        Route::delete('{id}', [RoleController::class, 'destroy']); // API15
    });

    // Project Management Routes (API16-API22)
    Route::prefix('projects')->group(function () {
        Route::get('/', [ProjectController::class, 'index']); // API16
        Route::get('filter', [ProjectController::class, 'filter']); // API17
        Route::post('/', [ProjectController::class, 'store']); // API18
        Route::get('{id}', [ProjectController::class, 'show']); // API16b (show)
        Route::put('{id}', [ProjectController::class, 'update']); // API19
        Route::delete('{id}', [ProjectController::class, 'destroy']); // API20
        Route::post('{id}/assign-pm', [ProjectController::class, 'assignPM']); // API21
        Route::post('{id}/assign-members', [ProjectController::class, 'assignMembers']); // API22
    });

    // Settings Routes (API23-API24)
    Route::prefix('settings')->group(function () {
        Route::get('/', [SettingController::class, 'index']); // API23
        Route::put('/', [SettingController::class, 'update']); // API24
    });

    // Schedule Routes (API25-API26)
    Route::prefix('schedules')->group(function () {
        Route::get('/', [SettingController::class, 'getSchedule']); // API25
        Route::put('/', [SettingController::class, 'updateSchedule']); // API26
    });

    // Issue Management Routes (API27-API33)
    Route::prefix('issues')->group(function () {
        Route::get('/', [IssueController::class, 'index']); // API27
        Route::get('filter', [IssueController::class, 'filter']); // API28
        Route::post('/', [IssueController::class, 'store']); // API29
        Route::put('{id}', [IssueController::class, 'update']); // API30
        Route::delete('{id}', [IssueController::class, 'destroy']); // API31
        Route::post('{id}/comments', [IssueController::class, 'addComment']); // API32
    });

    // Log Routes (API33)
    Route::prefix('logs')->group(function () {
        Route::get('/', [IssueController::class, 'getLogs']); // API33
    });
});


