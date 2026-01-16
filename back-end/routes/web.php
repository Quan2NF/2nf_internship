<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/reset-password/{token}', function (string $token) {
  
    return response()->json([
        'message' => 'This URL should be handled by your frontend application',
        'token' => $token,
        'email' => request('email')
    ]);
})->name('password.reset');
