<?php

namespace App\Services\Implementations;

use App\Models\Role;
use App\Repositories\Interfaces\IRoleRepository;
use App\Services\Interfaces\IRoleService;
use Illuminate\Support\Collection;

class RoleService implements IRoleService
{
    public function __construct(
        private readonly IRoleRepository $roleRepository
    ) {}
    public function list(): Collection
    {
        return Role::query()
            ->select(['id', 'code', 'name'])
            ->orderBy('code')
            ->get();
    }
    public function create(array $data)
    {
        return $this->roleRepository->create($data);
    }
    public function update(int $id, array $data): bool
    {
        return $this->roleRepository->update($id, $data);
    }
    public function delete(int $id): bool
    {
        // ngăn không cho xóa admin
        $role = $this->roleRepository->find($id);
        if ($role instanceof Role && $role->code === 'ADMIN') {
            return false;
        }
        return $this->roleRepository->delete($id);
    }
}
