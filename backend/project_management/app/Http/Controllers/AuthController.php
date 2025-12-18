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
    ]);
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

        return response()->json(['message' => 'Logged out successfully']);
    }
}