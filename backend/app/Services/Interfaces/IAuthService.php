<?php

namespace App\Services\Interfaces;

use App\Data\Auth\LoginData;
use App\Data\Auth\AuthResponseData;
use App\Data\Auth\ForgotPasswordData;
use App\Data\Auth\ResetPasswordData;
use App\Models\User;
use Illuminate\Http\Request;

interface IAuthService
{
    public function login(LoginData $data);
    
    public function logout(Request $request): void;

    public function forgotPassword(ForgotPasswordData $data): void;

    public function resetPassword(ResetPasswordData $data): void;
}
