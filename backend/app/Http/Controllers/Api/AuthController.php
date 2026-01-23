<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Data\Authentication\LoginRequestData;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Data\Authentication\ResetPasswordRequestData;
use App\Data\Authentication\ForgotPasswordRequestData;
use App\Contracts\Service\AuthenticationServiceInterface;

class AuthController extends Controller
{
    public function __construct(
        protected AuthenticationServiceInterface $authService
    ){}

    public function login(LoginRequest $request)
    {
        return $this->authService->login(
            new LoginRequestData(
                $request->email,
                $request->password
            )
        );
    }

    public function logout()
    {
        return $this->authService->logout();
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        return $this->authService->forgotPassword(
            new ForgotPasswordRequestData(
                $request->email,
            )
        );
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        return $this->authService->resetPassword(
            new ResetPasswordRequestData(
                $request->email,
                $request->password,
                $request->password_confirmation,
                $request->token,
            )
        );
    }
}
