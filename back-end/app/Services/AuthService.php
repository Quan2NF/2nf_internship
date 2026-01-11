<?php

namespace App\Services;

use App\Data\LoginData;
use App\Exceptions\BusinessException;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService implements AuthServiceInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $users
    ) {}

    public function login(LoginData $data): array
    {
        try {
            // Step 1.2.1: fetch active user by email
            $user = $this->users->findActiveByEmail($data->email);
            if (!$user) {
                throw new BusinessException('ERR_LOGIN_FAILED', 401);
            }

            // Step 1.2.2: check password
            if (!Hash::check($data->password, $user->password)) {
                throw new BusinessException('ERR_LOGIN_FAILED', 401);
            }

            // Step 1.3: create Sanctum token
            $token = $user->createToken('auth_token')->plainTextToken;

            // Step 1.4: check isAdmin
            $isAdmin = $this->users->userIsAdmin($user);

            // Step 1.5: build response data
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar ? url($user->avatar) : null,
                'isAdmin' => $isAdmin,
                'accessToken' => $token,
                'tokenType' => 'Bearer',
            ];
        } catch (BusinessException $e) {
            throw $e; // bubble up
        } catch (\Throwable $e) {
            throw new BusinessException('ERR_UNKNOWN', 500);
        }
    }
}
