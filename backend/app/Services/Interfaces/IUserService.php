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

    // AP05/AP06 
    public function paginateFiltered(array $filters, int $perPage = 15): LengthAwarePaginator;

    // AP10/AP11 
    public function assignSystemRoles(int $userId, array $roleCodes, string $mode = 'sync'): void;

    public function getSystemRoles(int $userId): array;

    //role theo project 
    public function assignRolesInProject(int $projectId, int $userId, array $roleCodes): void;

    public function getRolesInProject(int $projectId, int $userId): array;
}
