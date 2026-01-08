<?php

namespace App\Services\Interfaces;

use App\Data\Auth\LoginData;
use App\Data\Auth\AuthResponseData;
use App\Data\Auth\ForgotPasswordData;
use App\Models\User;

interface IAuthService
{
    public function login(LoginData $data);
    
    public function logout(User $user): void;

    public function forgotPassword(ForgotPasswordData $data): void;

    public function resetPassword(array $payload): void;
}
