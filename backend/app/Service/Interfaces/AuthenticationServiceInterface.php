<?php

namespace App\Service\Interfaces;

use App\Http\Responses\ApiResponse;

use App\Data\Authentication\LoginRequestData;
use App\Data\Authentication\ForgotPasswordRequestData;
use App\Data\Authentication\LogoutRequestData;
use App\Data\Authentication\ResetPasswordRequestData;

interface AuthenticationServiceInterface
{
    public function Login(LoginRequestData $data): ApiResponse;

    public function ForgotPassword(ForgotPasswordRequestData $data): ApiResponse;

    public function ResetPassword(ResetPasswordRequestData $data): ApiResponse;

    public function Logout(LogoutRequestData $data): ApiResponse;
}