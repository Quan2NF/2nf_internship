<?php

namespace App\Http\Controllers;
use App\DTOs\LoginDto;
use App\Services\Interfaces\IAuthService;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected IAuthService $authService;

    public function __construct(IAuthService $authService)
    {
        $this->authService = $authService;
    }
    public function register(Request $request)
    {
        // Validate input
        $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6',
        'phone' => 'nullable|string|max:20',
        'role' => 'required|string|in:user,admin',
        'status' => 'required|boolean|in:0,1', // 0 : đã nghỉ, 1 : đang làm
    ]);
        $validated['joined_at'] = now();
        $user = $this->authService->register($validated);

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'user' => UserTransformer::transform($user),
            'token' => $token,
        ], 201);
    }

    // LOGIN
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $loginData = new LoginDto(...$validated);

        $login = $this->authService->login($loginData);

        if (!$login) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return response()->json([
            'user' => UserTransformer::transform($login['user']),
            'token' => $login['token'],
        ]);
    }

    // LOGOUT
    public function logout(Request $request)
    {
        $this->authService->logout($request->user());

        return response()->json(['message' => 'Đăng xuất thành công']);
    }
}