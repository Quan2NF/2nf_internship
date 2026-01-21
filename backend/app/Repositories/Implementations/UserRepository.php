<?php

namespace App\Repositories\Implementations;

use App\Models\User;
use App\Repositories\Interfaces\IUserRepository;

class UserRepository extends BaseRepository implements IUserRepository
{
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function findByEmail(string $email): ?User
    {
        return $this->first([
            'email' => $email,
        ]);
    }

    public function findByEmployeeCode(string $employeeCode): ?User
    {
        return $this->first([
            'employee_code' => $employeeCode,
        ]);
    }

}
