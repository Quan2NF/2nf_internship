<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    public function all(): Collection;

    public function find(int $id): ?User;

    public function findByEmail(string $email): ?User;

    public function findByEmployeeCode(string $employeeCode): ?User;

    public function create(array $data): User;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    public function forceDelete(int $id): bool;

    public function restore(int $id): bool;

    public function getActive(): Collection;

    public function getInactive(): Collection;
}
