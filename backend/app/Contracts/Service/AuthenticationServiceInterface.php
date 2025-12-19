<?php

namespace App\Contracts\Service;

use App\Http\Responses\ApiResponse;

use App\Data\Authentication\AuthenticationData;

interface AuthenticationServiceInterface
{
    public function login(AuthenticationData $data): ApiResponse;

    public function forgotPassword(AuthenticationData $data): ApiResponse;

    public function resetPassword(AuthenticationData $data): ApiResponse;

    public function logout(AuthenticationData $data): ApiResponse;
}