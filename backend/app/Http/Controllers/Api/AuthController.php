<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Data\Auth\LoginData;
use App\Services\Interfaces\IAuthService;
use Illuminate\Http\Request;
use App\Data\Auth\ForgotPasswordData;
use App\Data\Common\ApiSuccessResponseData;
use App\Http\Controllers\Concerns\ApiResponse;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Data\Auth\ResetPasswordData;
use Illuminate\Support\Facades\Auth;
use App\Data\User\UserData;



class AuthController extends Controller
{
    private IAuthService $authService;

    public function __construct(IAuthService $authService)
    {
        $this->authService = $authService;
    }
    //public function __construct(protected IAuthService $authService) {} co the de kieu nay cho gon

    public function login(
    LoginRequest $request,
    IAuthService $authService
    ) {
        $data = LoginData::from($request->validated());

        $userData = $authService->login($data);

        return $this->success(
            message: 'LOGIN_SUCCESS',
            data: $userData 
        );
    }

    public function logout(Request $request, IAuthService $authService)
    {
        $authService->logout($request);

        return $this->success(
            message: 'LOGOUT_SUCCESS'
        );
    }

    public function me(Request $request)
    {
        return $this->success(
            message: 'GET_USER_SUCCESS',
            data: UserData::fromModel($request->user())
        );
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $data = ForgotPasswordData::from($request->validated());

        $this->authService->forgotPassword($data);

        return $this->success(
            message: 'RESET_PASSWORD_EMAIL_SENT'
        );
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $data = ResetPasswordData::from($request->validated());

        $this->authService->resetPassword($data);

        return $this->success(
            message: 'PASSWORD_RESET_SUCCESS'
        );
    }

}
