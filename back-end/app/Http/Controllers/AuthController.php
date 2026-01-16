<?php

namespace App\Http\Controllers;

use App\Data\LoginData;
use App\Data\ForgotPasswordData;
use App\Data\ResetPasswordData;
use App\Exceptions\BusinessException;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Services\AuthServiceInterface;
use Illuminate\Http\Request;

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

    // GET USER 

  
 public function profile(Request $request)
{   //Chuyển logic vào Service Layer
    $user = $request->user();  //Lấy user từ auth:sanctum middleware

    try {
        $result = $this->authService->profile($user);  //Gọi Service

        return response()->json([
            'data' => $result,// đúng format
        ], 200);
    } catch (BusinessException $e) {
        return response()->json([
            'statusCode' => $e->getStatusCode(),
            'message' => $e->getMessage(),
        ], $e->getStatusCode());
    }
}

    // LOGOUT (Sanctum token)
    public function logout(Request $request)
    {
        try {
            $this->authService->logout($request);

            return response()->json([
                'message' => 'Logout successfully'
            ], 200);
        } catch (BusinessException $e) {
            return response()->json([
                'statusCode' => $e->getStatusCode(),
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }
    }

    // FORGOT PASSWORD
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $data = ForgotPasswordData::fromRequest($request);

        try {
            $this->authService->forgotPassword($data);

            return response()->json([
                'message' => 'Reset password email sent'
            ], 200);
        } catch (BusinessException $e) {
            return response()->json([
                'statusCode' => $e->getStatusCode(),
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }
    }

    // RESET PASSWORD
    public function resetPassword(ResetPasswordRequest $request)
    {
        $data = ResetPasswordData::fromRequest($request);

        try {
            $this->authService->resetPassword($data);

            return response()->json([
                'message' => 'Password reset successfully'
            ], 200);
        } catch (BusinessException $e) {
            return response()->json([
                'statusCode' => $e->getStatusCode(),
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }
    }
}

