<?php

namespace App\Service;

use App\Service\Interfaces\AuthenticationServiceInterface;
use App\Http\Responses\ApiResponse;
use App\Data\Authentication\AuthenticationData;

class AuthenticationService implements AuthenticationServiceInterface
{
    public function login(AuthenticationData $data): ApiResponse
    {
        throw new \Exception('Not implemented');
    }

    public function forgotPassword(AuthenticationData $data): ApiResponse
    {
        throw new \Exception('Not implemented');
    }

    public function resetPassword(AuthenticationData $data): ApiResponse
    {
        throw new \Exception('Not implemented');
    }

    public function logout(AuthenticationData $data): ApiResponse
    {
        throw new \Exception('Not implemented');
    }
}