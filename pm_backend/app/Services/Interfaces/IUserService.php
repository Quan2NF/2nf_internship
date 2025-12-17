<?php

namespace App\Services\Interfaces;

use App\DTOs\UpdateUserDto;
use App\Models\User;

interface IUserService {
    public function getAll() : array;
    public function getbyId(int $id) : User;
    public function update(User $user, UpdateUserDto $dto): User;

    public function delete(User $user): void;
}