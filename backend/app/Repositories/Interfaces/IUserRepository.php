<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface IUserRepository extends IBaseRepository
{
    public function findByEmail(string $email): ?User;
    public function findByEmployeeCode(string $employeeCode): ?User;

}
