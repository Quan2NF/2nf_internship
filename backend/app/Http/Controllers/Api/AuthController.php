<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Data\Auth\LoginData;
use App\Services\Interfaces\IAuthService;
use Illuminate\Http\Request;
use App\Data\Auth\ForgotPasswordData;


class AuthController extends Controller
{
    private IAuthService $authService;

    public function __construct(IAuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(
        LoginData $data,
        IAuthService $authService
    ) {
        return $authService->login($data);
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user());

        return response()->json([
            'message' => 'Logged out'
        ]);
    }

    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $data = ForgotPasswordData::from($request->validate([
            'email' => ['required', 'email'],
        ]));

        $this->authService->forgotPassword($data);

        return response()->json([
            'message' => 'Reset password email sent',
        ]);
    }

    public function resetPassword(Request $request)
    {
        $payload = $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $this->authService->resetPassword($payload);

        return response()->json([
            'message' => 'Password reset successfully',
        ]);
    }

}
