<?php

namespace App\Contracts\Service;

use App\Data\Authentication\AuthenticationData;
use App\Data\Response\ApiResponseData;

interface AuthenticationServiceInterface
{
    public function login(AuthenticationData $data): ApiResponseData;

    public function forgotPassword(AuthenticationData $data): ApiResponseData;

    public function resetPassword(AuthenticationData $data): ApiResponseData;

    public function logout(AuthenticationData $data): ApiResponseData;
}