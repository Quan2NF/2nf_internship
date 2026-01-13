<?php

namespace App\Services;

use App\Data\Auth\LoginData;
use App\Data\Auth\UserData;
use App\Exceptions\AuthenticationException;
use App\Exceptions\AuthorizationException;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    public function login(LoginData $data): array
    {
        $user = $this->userRepository->findByEmail($data->email);

        if (!$user || !Hash::check($data->password, $user->password)) {
            throw new AuthenticationException('Invalid credentials');
        }

        if ($user->is_active !== User::STATUS_ACTIVE) {
            throw new AuthorizationException('Account is inactive');
        }

        // Don't delete previous tokens on login
        // Multiple tokens allow for multiple active sessions (e.g., multiple devices)
        return $this->issueToken($user);
    }

    public function logout(User $user): void
    {
        $token = $user->currentAccessToken();

        // Only delete actual PersonalAccessToken instances (not TransientToken used in tests)
        if ($token && $token instanceof \Laravel\Sanctum\PersonalAccessToken) {
            $token->delete();
        }
    }

    public function logoutAll(User $user): void
    {
        // Delete all real tokens (PersonalAccessToken)
        $user->tokens()->delete();
    }

    public function me(User $user): UserData
    {
        return UserData::from($user);
    }

    public function refresh(User $user): array
    {
        // Revoke current token
        $token = $user->currentAccessToken();
        if ($token) {
            $token->delete();
        }

        // Issue new token
        return $this->issueToken($user);
    }

    protected function issueToken(User $user): array
    {
        $token = $user->createToken(
            config('auth.token_name', 'auth'),
            ['*']
        )->plainTextToken;

        return [
            'user' => UserData::from($user),
            'token' => $token,
        ];
    }
}
