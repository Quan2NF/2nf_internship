<?php
namespace App\Services\Implements;

use App\DTOs\UpdateUserDto;
use App\Models\User;
use App\Repositories\Interfaces\IUserRepository;
use App\Services\Interfaces\IUserService;
use App\Services\Implements\BaseService;

class UserService extends BaseService implements IUserService 
{
   public function __construct(protected IUserRepository $userRepo)
    {
        parent::__construct($userRepo); // truyền xuống BaseService
        
    }
    public function updateUser(User $user, UpdateUserDto $dto): User
    {
        
        $data = [
            'name'  => $dto->name ?? $user->name,
            'email' => $dto->email ?? $user->email,
            'phone' => $dto->phone ?? $user->phone,
        ];

        // Sử dụng BaseService::update
        $this->update($user->id, $data);

        // Sync roles nếu có
        if (!empty($dto->roles)) {
            $user->roles()->sync($dto->role); // roles là mảng ID hoặc code
        }

        // Reload user kèm roles
        return $user->refresh()->load('roles');
    }
}