<?php

namespace App\Services;

use App\Data\LoginData;

interface AuthServiceInterface
{
    /**
     * Perform login per spec and return response data array
     * [id, name, email, avatar, isAdmin]
     */
    public function login(LoginData $data): array;
}
