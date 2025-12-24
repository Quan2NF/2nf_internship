<?php

namespace App\Services\Interfaces;

use App\DTOs\LoginDto;
use App\Models\User;

interface IAuthService extends IBaseService {
    public function register(array $data): User;

    public function login(LoginDto $data): ?array;

    public function logout(User $user): void;
    #public function forgotPassword() : void;
    #public function resetPassword() : void;
}