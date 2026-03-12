<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PositionController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.reset');
});

Route::get('/me', function () {
    /** @var \App\Models\User|null $user */
    $user = Auth::user();
    
    $user?->load('positions');

    return response()->json([
        'response_code' => 'R_CMN_200_01',
        'data' => $user
    ]);
});

Route::get('/ping', function () {
    return 'pong';
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

Route::prefix('projects')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [ProjectController::class, 'getFilteredList']);
    Route::post('/', [ProjectController::class, 'create']);
    Route::get('{project}', [ProjectController::class, 'view']);
    Route::patch('{project}', [ProjectController::class, 'update']);
    Route::delete('{project}', [ProjectController::class, 'delete']);
    Route::get('{project}/pm', [ProjectController::class, 'getPM']);
    Route::put('{project}/pm', [ProjectController::class, 'assignPM']);
    Route::get('{project}/members', [ProjectController::class, 'getMembers']);
    Route::put('{project}/members', [ProjectController::class, 'assignMembers']);
    Route::get('{project}/settings', [ProjectController::class, 'getSettings']);
    Route::patch('{project}/settings', [ProjectController::class, 'updateSettings']);
    Route::get('{project}/schedule', [ProjectController::class, 'getSchedule']);
    Route::put('{project}/schedule', [ProjectController::class, 'updateSchedule']);
});

Route::prefix('projects/{project}/tasks')->middleware('auth:sanctum')->scopeBindings()->group(function () {
    Route::get('/', [TaskController::class, 'getFilteredList']);
    Route::post('/', [TaskController::class, 'create']);
    Route::get('{task}', [TaskController::class, 'view']);
    Route::patch('{task}', [TaskController::class, 'update']);
    Route::delete('{task}', [TaskController::class, 'delete']);
    Route::get('{task}/comments', [TaskController::class, 'getComments']);
    Route::post('{task}/comments', [TaskController::class, 'postComment']);
    Route::get('{task}/logs', [TaskController::class, 'getAuditLogs']);
});
