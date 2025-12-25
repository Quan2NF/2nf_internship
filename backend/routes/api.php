<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\IssueController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('logout-all', [AuthController::class, 'logoutAll']);
        Route::get('me', [AuthController::class, 'me']);
    });
});

Route::middleware('auth:sanctum')->group(function () {
    // Projects Routes
    Route::apiResource('projects', ProjectController::class)
        ->only(['index', 'store', 'show', 'update', 'destroy']);

    // Issues Routes
    Route::apiResource('issues', IssueController::class);

    // Specialized Issue Routes
    Route::get('issues/by-project/{projectId}', [IssueController::class, 'byProject']);
    Route::get('issues/by-assignee/{userId}', [IssueController::class, 'byAssignee']);
    Route::get('issues/my-issues', [IssueController::class, 'myIssues']);
    Route::get('issues/reported-by-me', [IssueController::class, 'reportedByMe']);
    Route::get('issues/open', [IssueController::class, 'open']);
    Route::get('issues/closed', [IssueController::class, 'closed']);
    Route::get('issues/overdue', [IssueController::class, 'overdue']);
});

