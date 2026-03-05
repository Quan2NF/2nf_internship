<?php

namespace App\Service;

use App\Contracts\Service\AuthenticationServiceInterface;
use App\Data\Authentication\ForgotPasswordRequestData;
use App\Data\Authentication\LoginRequestData;
use App\Data\Authentication\LoginResponseData;
use App\Data\Authentication\ResetPasswordRequestData;
use App\Data\Response\ApiResponseData;
use App\Enums\ResponseCode;
use App\Http\Responses\ApiResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class AuthenticationService implements AuthenticationServiceInterface
{
    public function login(LoginRequestData $data): ApiResponseData
    {
        /** @var User|null $user */
        $user = User::where('email', $data->email)
                    ->where('is_active', true)
                    ->first();

        if (!$user || !Hash::check($data->password, $user->password)) {
            return ApiResponse::from(ResponseCode::INVALID_CREDENTIALS);
        }

        Auth::login($user, $data->remember);
        request()->session()->regenerate();

        return ApiResponse::from(ResponseCode::SUCCESS, new LoginResponseData(
            $user->id,
            $user->name,
            $user->email,
            $user->avatar,
            $user->hasSystemPosition('ADMIN')
        ));
    }

    public function forgotPassword(ForgotPasswordRequestData $data): ApiResponseData
    {
        $status = Password::sendResetLink([
            'email' => $data->email,
        ]);

        // Do NOT expose whether the email exists
        return ApiResponse::from(ResponseCode::SUCCESS);
    }

    public function resetPassword(ResetPasswordRequestData $data): ApiResponseData
    {
        $status = Password::reset(
            [
                'email' => $data->email,
                'password' => $data->password,
                'password_confirmation' => $data->password,
                'token' => $data->token,
            ],
            function ($user) use ($data) {
                $user->forceFill([
                    'password' => Hash::make($data->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            return ApiResponse::from(ResponseCode::INVALID_PARAMETER);
        }

        return ApiResponse::from(ResponseCode::SUCCESS);
    }

    public function logout(): ApiResponseData
    {
        Auth::logout(); // remove user from session

        request()->session()->invalidate(); // destroy session data
        request()->session()->regenerateToken(); // new CSRF token

        return ApiResponse::from(ResponseCode::SUCCESS);
    }
}