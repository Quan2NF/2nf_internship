<?php

namespace App\Services\Implementations;

use App\Services\Interfaces\IAuthService;
use App\Repositories\Interfaces\IUserRepository;
use App\Data\Auth\LoginData;
use App\Data\Auth\AuthResponseData;
use App\Data\User\UserData;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\AuthenticationException;

class AuthService implements IAuthService
{
    public function __construct(
        private IUserRepository $userRepository
    ) {}

    public function login(LoginData $data): AuthResponseData
    {
        $user = $this->userRepository->findByEmail($data->email);

        if (!$user || !Hash::check($data->password, $user->password)) {
            throw new AuthenticationException('Invalid credentials');
        }

        $token = $user->createToken('api')->plainTextToken;

        return new AuthResponseData(
            token: $token,
            token_type: 'Bearer',
            user: UserData::fromModel($user)
        );
    }
}
