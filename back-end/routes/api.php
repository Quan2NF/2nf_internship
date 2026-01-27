<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

// Protected routes - require auth:sanctum middleware
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::middleware('admin')->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::put('/users/{user}', [UserController::class, 'update']);
        Route::delete('/users/{user}', [UserController::class, 'destroy']);
        Route::post('/users/{user}/positions', [UserController::class, 'assignPositions']);
        Route::get('/users/{user}/positions', [UserController::class, 'listPositions']);

        // API12-15: Roles (system permissions - project based)
        Route::get('/roles', [RoleController::class, 'index']);
        Route::post('/roles', [RoleController::class, 'store']);
        Route::put('/roles/{role}', [RoleController::class, 'update']);
        Route::delete('/roles/{role}', [RoleController::class, 'destroy']);
    });

    // Projects endpoints - require role checks inside controller/service
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::get('/projects/filter', [ProjectController::class, 'index']);

    Route::post('/projects', [ProjectController::class, 'store']);
    Route::put('/projects/{project}', [ProjectController::class, 'update']);
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy']);

    Route::post('/projects/{project}/assign-pm', [ProjectController::class, 'assignPm']);
    Route::post('/projects/{project}/members', [ProjectController::class, 'assignMembers']);

    Route::post('/projects/{project}/settings', [ProjectController::class, 'setSettings']);
    Route::put('/projects/{project}/settings', [ProjectController::class, 'updateSettings']);

    Route::get('/projects/{project}/schedule', [ProjectController::class, 'getSchedule']);
    Route::put('/projects/{project}/schedule', [ProjectController::class, 'updateSchedule']);

    // API27: List Of Issues (list/filter tasks/issues)
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::put('/tasks/{id}', [TaskController::class, 'update']);
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);
    Route::post('/tasks/{id}/comments', [TaskController::class, 'comment']);
    Route::get('/tasks/{id}/logs', [TaskController::class, 'logs']);
});


