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
        Route::get('/me', [AuthController::class, 'me']); //API THÊM ĐỂ TEST XEM AI ĐANG ĐĂNG NHẬP

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

        Route::get('/projects/my', [ProjectController::class, 'myProjects']);
        Route::post('/projects', [ProjectController::class, 'store']);
        Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);

        Route::get('/tasks', [TaskController::class, 'index']); 
        Route::post('/tasks', [TaskController::class, 'store']);
        Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);
    });

    
});

Route::get('/reset-password/{token}', function ($token) {
    return response()->json([
        'token' => $token,
        'email' => request('email'),
    ]);
})->name('password.reset');



//require __DIR__.'/auth.php';
