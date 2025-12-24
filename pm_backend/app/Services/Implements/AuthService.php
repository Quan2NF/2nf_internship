<?php
namespace App\Services\Implements;

use App\DTOs\LoginDto;
use App\DTOs\GetUserDto;
use App\Models\User;
use App\Repositories\Interfaces\IUserRepository;
use App\Services\Interfaces\IAuthService;
use App\Services\Implements\BaseService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Exception;

class AuthService extends BaseService implements IAuthService 
{

    public function __construct(protected IUserRepository $userRepo)
    {
        parent::__construct($userRepo); // truyền xuống BaseService
    }
    public function register(array $data): User
    {
        try {
            $data['password'] = Hash::make($data['password']);
            return $this->create($data);
        } catch (Exception $e) {
            Log::error('Error in AuthService::register - ' . $e->getMessage());
            throw $e;
        }
    }

    public function login(LoginDto $data) : ?array
    {
        try {
            $user = $this->userRepo->findByEmail($data->email);

            if (!$user || !Hash::check($data->password, $user->password)) {
                return null;
            }

            $token = $user->createToken('api_token')->plainTextToken;
            


           $userDto = new GetUserDto(
           id: $user->id,
           name: $user->name,
           email: $user->email,
           avatar: $user->avatar,
           isAdmin: false //fix sau
           );
            return [
                'user' => $userDto,
                'token' => $token,
            ];
        } catch (Exception $e) {
            Log::error('Error in AuthService::login - ' . $e->getMessage());
            throw $e;
        }
    }

    public function logout(User $user): void
    {
        try {
            $user->tokens()->delete();
        } catch (Exception $e) {
            Log::error('Error in AuthService::logout - ' . $e->getMessage());
            throw $e;
        }
    }
}