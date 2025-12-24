<?php

namespace App\Services\Interfaces;

use App\DTOs\UpdateUserDto;
use App\Models\User;

interface IUserService extends IBaseService {
     public function updateUser(User $user, UpdateUserDto $dto): User;
}