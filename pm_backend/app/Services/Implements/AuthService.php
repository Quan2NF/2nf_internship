<?php
namespace App\Services\Implements;

use App\DTOs\LoginDto;
use App\Models\User;
use App\Repositories\Interfaces\IUserRepository;
use App\Services\Interfaces\IAuthService;
use Illuminate\Support\Facades\Hash;

class AuthService implements IAuthService {
    protected IUserRepository $userRepo;

    public function __construct(IUserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }
    public function register(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        return $this->userRepo->create($data);
    }

    public function login(LoginDto $data) : ?array
    {
        $user = $this->userRepo->findbyEmail($data->email);
        if (!$user || !Hash::check($data->password, $user->password)) {
            return null;
        }

        $token = $user->createToken('api_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

public function logout(User $user): void
{
    $user->tokens()->delete();
}
}