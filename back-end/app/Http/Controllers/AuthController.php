<?php

namespace App\Http\Controllers;

use App\Data\LoginData;
use App\Exceptions\BusinessException;
use App\Http\Requests\LoginRequest;
use App\Services\AuthServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Models\User;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthServiceInterface $authService
    ) {}

    // LOGIN - layered: FormRequest -> DTO -> Service
    public function login(LoginRequest $request)
    {
        $data = LoginData::fromRequest($request);

        try {
            $result = $this->authService->login($data);

            return response()->json([
                'data' => $result,
            ], 200);
        } catch (BusinessException $e) {
            return response()->json([
                'statusCode' => $e->getStatusCode(),
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }
    }

    // GET USER (cần auth:sanctum)
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    // LOGOUT (Sanctum token)
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()?->delete();

        return response()->json([
            'message' => 'Logout successfully'
        ], 200);
    }

    // FORGOT PASSWORD
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'message' => 'Email not found'
            ], 404);
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'message' => 'Reset password email sent'
            ], 200);
        }

        return response()->json([
            'message' => 'Cannot send reset password email'
        ], 500);
    }

    // RESET PASSWORD
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            return response()->json([
                'message' => __($status)
            ], 400);
        }

        return response()->json([
            'message' => 'Password reset successfully'
        ]);
    }
}

