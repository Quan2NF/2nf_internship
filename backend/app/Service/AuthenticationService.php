<?php

namespace App\Service;

use App\Models\User;
use App\Enums\ResponseCode;
use App\Http\Responses\ApiResponse;
use Illuminate\Support\Facades\Hash;
use App\Data\Response\ApiResponseData;
use App\Data\Authentication\LoginRequestData;
use App\Data\Authentication\ResetPasswordRequestData;
use App\Data\Authentication\ForgotPasswordRequestData;
use App\Contracts\Service\AuthenticationServiceInterface;
use App\Data\Authentication\LoginResponseData;

class AuthenticationService implements AuthenticationServiceInterface
{
    public function login(LoginRequestData $data): ApiResponseData
    {
        $user = User::where('email', $data->email)
                    ->where('is_active', true)
                    ->first();

        if (!$user || !Hash::check($data->password, $user->password)) {
            return ApiResponse::from(ResponseCode::INVALID_CREDENTIALS);
        }

        $token = $user->createToken('access_token')->plainTextToken;

        return ApiResponse::from(ResponseCode::SUCCESS, new LoginResponseData(
            $user->employee_code,
            $user->name,
            $user->email,
            $token
        ));
    }

    public function forgotPassword(ForgotPasswordRequestData $data): ApiResponseData
    {
        throw new \Exception('Not implemented');
    }

    public function resetPassword(ResetPasswordRequestData $data): ApiResponseData
    {
        throw new \Exception('Not implemented');
    }

    public function logout(): ApiResponseData
    {
        throw new \Exception('Not implemented');
    }
}