<?php

namespace App\Contracts\Service;

use App\Data\Authentication\ForgotPasswordRequestData;
use App\Data\Authentication\LoginRequestData;
use App\Data\Authentication\ResetPasswordRequestData;
use App\Data\Response\ApiResponseData;

interface AuthenticationServiceInterface
{
    public function login(LoginRequestData $data): ApiResponseData;

    public function forgotPassword(ForgotPasswordRequestData $data): ApiResponseData;

    public function resetPassword(ResetPasswordRequestData $data): ApiResponseData;

    public function logout(): ApiResponseData;
}