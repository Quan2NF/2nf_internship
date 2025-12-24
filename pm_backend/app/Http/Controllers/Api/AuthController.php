<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\Interfaces\IAuthService;
use App\DTOs\LoginDto;
use App\DTOs\RegisterDto;
use App\Http\Responses\ApiResponse;
use App\Enums\ResponseCode;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(protected IAuthService $authService)
    {
    }

    /**
     * Register a new user
     */
    public function register(RegisterRequest $request)
    {
        try {
            $data = $request->validated();

            $dto = new RegisterDto(
                employee_code: $data['employee_code'],
                name: $data['name'],
                email: $data['email'],
                password: $data['password'],
                phone_number: $data['phone_number'] ?? null,
                birthday: $data['birthday'] ?? null,
                gender: $data['gender'] ?? null,
            );

            $user = $this->authService->register((array) $dto);

            return ApiResponse::success([
                'message' => 'Đăng ký người dùng thành công',
                'user' => $user,
            ]);
        } catch (\Exception $e) {
            return ApiResponse::error(ResponseCode::SERVER_ERROR, $e->getMessage());
        }
    }

    /**
     * Login user and get token
     */
    public function login(LoginRequest $request)
    {
        try {
            $dto = new LoginDto(
                email: $request->input('email'),
                password: $request->input('password')
            );

            $result = $this->authService->login($dto);

            if (!$result) {
                return ApiResponse::error(ResponseCode::UNAUTHORIZED, 'Sai thông tin đăng nhập');
            }

            return ApiResponse::success($result);
        } catch (\Exception $e) {
            return ApiResponse::error(ResponseCode::SERVER_ERROR, $e->getMessage());
        }
    }

    /**
     * Logout user
     */
    public function logout()
    {
         try {
            $user = Auth::user();

            if (!$user) {
                return ApiResponse::error(ResponseCode::UNAUTHORIZED, 'Người dùng chưa đăng nhập');
            }

            $this->authService->logout($user);

            return ApiResponse::success([
                'message' => 'Đăng xuất thành công',
            ]);
        } catch (\Exception $e) {
            return ApiResponse::error(ResponseCode::SERVER_ERROR, $e->getMessage());
        }
    }
}
