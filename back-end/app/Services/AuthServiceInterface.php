<?php

namespace App\Services;

use App\Data\LoginData;
use App\Data\ForgotPasswordData;
use App\Data\ResetPasswordData;
use App\Models\User;
use Illuminate\Http\Request;

interface AuthServiceInterface
{
    /**
     * Perform login per spec and return response data array
     * [id, name, email, avatar, isAdmin]
     */
    public function login(LoginData $data): array;

    /**
     * Get user profile data
     */
    public function profile(User $user): array;

    /**
     * Send password reset link to email
     */
    public function forgotPassword(ForgotPasswordData $data): bool;

    /**
     * Reset password with token
     */
    public function resetPassword(ResetPasswordData $data): bool;

    /**
     * Logout current request context.
     * - If authenticated via Sanctum bearer token: revoke the current token.
     * - If authenticated via web session/cookie: logout and invalidate session.
     */
    public function logout(Request $request): bool;
}

