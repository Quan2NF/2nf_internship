<?php
namespace App\Services\Implements;

use App\DTOs\UpdateUserDto;
use App\Models\User;
use App\Repositories\Interfaces\IUserRepository;
use App\Services\Interfaces\IUserService;

class UserService implements IUserService {
    protected IUserRepository $userRepo;

    public function __construct(IUserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }
    public function getAll() : array {
       $userList = $this->userRepo->all();
       return $userList;
    }
    public function getbyId(int $id) : User {
       $user = $this->userRepo->find($id);
       return $user;
    }

    public function update(User $user, UpdateUserDto $dto): User {
       if ($dto->name !== null) {
        $user->name = $dto->name;
       }

    if ($dto->email !== null) {
        $user->email = $dto->email;
       }
     if ($dto->phone !== null) {
        $user->phone = $dto->phone;
       }
        if ($dto->role !== null) {
        $user->role = $dto->role;
       }
    $data = $dto->toArray();

    $this->userRepo->update($user->id, $data);

    // Reload user để chắc chắn dữ liệu mới
    return $this->userRepo->find($user->id);
    }

    public function delete(User $user): void {
        $this->userRepo->delete($user->id);
    }
}