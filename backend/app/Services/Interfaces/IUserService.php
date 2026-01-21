<?php

namespace App\Services\Interfaces;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface IUserService
{
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    public function find(int $id): ?User;

    public function create(array $data): User;

    public function update(int $id, array $data): bool;

    public function softDelete(int $id): bool;

    public function updateMyProfile(int $userId, array $data): bool;
    
    public function assignRolesInProject(int $projectId, int $userId, array $roleCodes): void;

    public function getRolesInProject(int $projectId, int $userId): array;
}
