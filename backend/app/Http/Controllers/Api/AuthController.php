<?php

namespace App\Http\Controllers\Api;

use App\Data\Auth\LoginData;
use App\Data\Auth\UserData;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected AuthService $authService
    ) {}
    public function login(LoginRequest $request): JsonResponse
    {
        $loginData = new LoginData(
            email: $request->input('email'),
            password: $request->input('password'),
        );

        $result = $this->authService->login($loginData);

        $userData = $result['user'] instanceof UserData ? $result['user'] : UserData::fromModel($result['user']);

        return $this->successResponse(
            data: [
                'user' => $userData,
                'token' => $result['token'],
            ],
            message: 'Đăng nhập thành công'
        );
    }

    /**
     * API02: Request password reset
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email|exists:users',
            ]);

            // Generate reset token and send email
            // This is a placeholder - implement actual password reset logic
            $resetToken = \Illuminate\Support\Str::random(60);

            return $this->successResponse(
                data: [
                    'message' => 'Kiểm tra email của bạn để đặt lại mật khẩu',
                ],
                message: 'Yêu cầu đặt lại mật khẩu đã được gửi'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                statusCode: 400
            );
        }
    }

    /**
     * API03: Reset password
     */
    public function resetPassword(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email|exists:users',
                'token' => 'required|string',
                'password' => 'required|min:8|confirmed',
            ]);

            // Verify token and reset password
            // This is a placeholder - implement actual password reset logic
            $user = \App\Models\User::where('email', $validated['email'])->first();

            if (!$user) {
                return $this->errorResponse(
                    message: 'Người dùng không tìm thấy',
                    statusCode: 404
                );
            }

            $user->update(['password' => $validated['password']]);

            return $this->successResponse(
                message: 'Đặt lại mật khẩu thành công'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                statusCode: 400
            );
        }
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return $this->successResponse(
            message: 'Đăng xuất thành công'
        );
    }

    public function logoutAll(Request $request): JsonResponse
    {
        $this->authService->logoutAll($request->user());

        return $this->successResponse(
            message: 'Đã đăng xuất từ tất cả các thiết bị'
        );
    }

    public function me(Request $request): JsonResponse
    {
        return $this->successResponse(
            data: UserData::fromModel($request->user()),
            message: 'Thông tin tài khoản'
        );
    }

    public function refresh(Request $request): JsonResponse
    {
        $result = $this->authService->refresh($request->user());

        $userData = $result['user'] instanceof UserData ? $result['user'] : UserData::fromModel($result['user']);

        return $this->successResponse(
            data: [
                'user' => $userData,
                'token' => $result['token'],
            ],
            message: 'Token đã được làm mới'
        );
    }
}
