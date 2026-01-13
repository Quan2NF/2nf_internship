<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class UserRepository implements UserRepositoryInterface
{
    protected User $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function find(int $id): ?User
    {
        return $this->model->find($id);
    }

    public function create(array $data): User
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $user = $this->find($id);
        if (!$user) {
            return false;
        }
        return $user->update($data);
    }

    public function delete(int $id): bool
    {
        $user = $this->find($id);
        if (!$user) {
            return false;
        }
        return $user->delete();
    }

    public function forceDelete(int $id): bool
    {
        $user = $this->model->withTrashed()->find($id);
        if (!$user) {
            return false;
        }
        return $user->forceDelete();
    }

    public function restore(int $id): bool
    {
        $user = $this->model->withTrashed()->find($id);
        if (!$user) {
            return false;
        }
        return $user->restore();
    }

    public function getTrashed(): Collection
    {
        return $this->model->onlyTrashed()->get();
    }

    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    public function findByEmployeeCode(string $employeeCode): ?User
    {
        return $this->model->where('employee_code', $employeeCode)->first();
    }

    public function getActive(): Collection
    {
        return $this->model->active()->get();
    }

    public function getInactive(): Collection
    {
        return $this->model->inactive()->get();
    }
}
