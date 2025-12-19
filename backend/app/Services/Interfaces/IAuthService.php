<?php

namespace App\Services\Interfaces;

use App\Data\Auth\LoginData;
use App\Data\Auth\AuthResponseData;

interface IAuthService
{
    public function login(LoginData $data): AuthResponseData;
}
