<?php

namespace App\Services;

use App\Data\Auth\LoginData;
use App\Data\Auth\RegisterData;
use App\Data\Auth\UserData;
use App\Events\User\UserRegistered;
use App\Exceptions\AuthenticationException;
use App\Exceptions\ValidationException;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    public function register(RegisterData $data): array
    {
        $user = $this->userRepository->create([
            'employee_code' => $data->employee_code,
            'name' => $data->name,
            'email' => $data->email,
            'password' => Hash::make($data->password),
            'phone_number' => $data->phone_number,
            'birthday' => $data->birthday,
            'gender' => $data->gender,
            'join_date' => $data->join_date ?? now()->toDateString(),
            'avatar' => $data->avatar,
            'is_active' => User::STATUS_ACTIVE,
        ]);

        event(new UserRegistered($user));

        return $this->issueToken($user);
    }

    public function login(LoginData $data): array
    {
        $user = $this->userRepository->findByEmail($data->email);

        if (!$user || !Hash::check($data->password, $user->password)) {
            throw new AuthenticationException('Invalid credentials');
        }

        if ($user->is_active !== User::STATUS_ACTIVE) {
            throw new AuthorizationException('Account is inactive');
        }

        if (!$data->remember) {
            $user->tokens()->delete();
        }

        return $this->issueToken($user);
    }

    public function logout(User $user): void
    {
        $token = $user->currentAccessToken();

        if ($token) {
            $token->delete();
        }
    }

    public function logoutAll(User $user): void
    {
        $user->tokens()->delete();
    }

    public function me(User $user): UserData
    {
        return UserData::from($user);
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
