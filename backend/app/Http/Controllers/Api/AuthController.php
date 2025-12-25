<?php

namespace App\Http\Controllers\Api;

use App\Data\Auth\LoginData;
use App\Data\Auth\RegisterData;
use App\Http\Controllers\Controller;
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

    public function register(RegisterData $data): JsonResponse
    {
        $result = $this->authService->register($data);

        return $this->createdResponse(
            $result,
            'User registered successfully'
        );
    }

    public function login(LoginData $data): JsonResponse
    {
        $result = $this->authService->login($data);

        return $this->successResponse(
            $result,
            'Login successful'
        );
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return $this->successResponse(
            null,
            'Logout successful'
        );
    }

    public function logoutAll(Request $request): JsonResponse
    {
        $this->authService->logoutAll($request->user());

        return $this->successResponse(
            null,
            'Logged out from all devices'
        );
    }

    public function me(Request $request): JsonResponse
    {
        return $this->successResponse(
            $this->authService->me($request->user())
        );
    }
}
