<?php

namespace App\Service;

use App\Contracts\Service\AuthenticationServiceInterface;
use App\Data\Response\ApiResponseData;
use App\Data\Authentication\AuthenticationData;

class AuthenticationService implements AuthenticationServiceInterface
{
    public function login(AuthenticationData $data): ApiResponseData
    {
        throw new \Exception('Not implemented');
    }

    public function forgotPassword(AuthenticationData $data): ApiResponseData
    {
        throw new \Exception('Not implemented');
    }

    public function resetPassword(AuthenticationData $data): ApiResponseData
    {
        throw new \Exception('Not implemented');
    }

    public function logout(AuthenticationData $data): ApiResponseData
    {
        throw new \Exception('Not implemented');
    }
}