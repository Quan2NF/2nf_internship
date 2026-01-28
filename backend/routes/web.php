<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RoleController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

//Route::middleware('auth')->group(function () {
//    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
//});


Route::middleware(['web'])->group(function () {

    Route::post('/login', [AuthController::class, 'login']); //API01
    Route::post('/logout', [AuthController::class, 'logout']); //API04
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']); //API02
    Route::post('/reset-password', [AuthController::class, 'resetPassword']); //API03
    Route::middleware('auth')->group(function () {
        Route::get('/me', [AuthController::class, 'me']); //API/me THÊM ĐỂ TEST XEM AI ĐANG ĐĂNG NHẬP

        Route::get('/users', [UserController::class, 'index']);            // API05/AP06
        Route::post('/users', [UserController::class, 'store']);           // API07
        Route::patch('/users/{id}', [UserController::class, 'update']);    // API08
        Route::delete('/users/{id}', [UserController::class, 'destroy']);  // API09
        Route::post('/users/assign-role', [UserController::class, 'assignRole']); // API10
        Route::get('/users/{id}/roles', [UserController::class, 'roles']);        // API11

        Route::get('/roles', [RoleController::class, 'index']);            // API12
        Route::post('/roles', [RoleController::class, 'store']);           // API13
        Route::patch('/roles/{id}', [RoleController::class, 'update']);    // API14
        Route::delete('/roles/{id}', [RoleController::class, 'destroy']);  // API15

        Route::get('/projects', [ProjectController::class, 'index']);      // API16 VA API17
        Route::post('/projects', [ProjectController::class, 'store']);     // API18
        Route::put('/projects/{id}', [ProjectController::class, 'update']); // API19
        Route::delete('/projects/{id}', [ProjectController::class, 'destroy']); // API20
        
        Route::post('/projects/{id}/assign-pm', [ProjectController::class, 'assignPm']);// API21: Assign PM
        Route::post('/projects/{id}/members', [ProjectController::class, 'assignMembers']);// API22: Assign Members
        Route::get('/projects/{id}/setting', [ProjectController::class, 'getSetting']);// API23: Get Setting (wikis + wiki_contents)
        Route::put('/projects/{id}/setting', [ProjectController::class, 'updateSetting']);// API24: Update Setting (wikis + wiki_contents)
        Route::get('/projects/{id}/schedule', [ProjectController::class, 'getSchedule']); // API25: Get Schedule (versions)
        Route::put('/projects/{id}/schedule', [ProjectController::class, 'updateSchedule']); // API26: Update Schedule (versions)

        Route::get('/tasks', [TaskController::class, 'index']);                 // API27 + API28
        Route::post('/tasks', [TaskController::class, 'store']);                // API29
        Route::patch('/tasks/{id}', [TaskController::class, 'update']);          // API30
        Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);        // API31
        Route::post('/tasks/{id}/comments', [TaskController::class, 'comment']); // API32
        Route::get('/tasks/{id}/logs', [TaskController::class, 'logs']);         // API33


        });

    
});

Route::get('/reset-password/{token}', function ($token) {
    return response()->json([
        'token' => $token,
        'email' => request('email'),
    ]);
})->name('password.reset');



//require __DIR__.'/auth.php';
