<?php

namespace App\Repositories\Implementations;

use App\Repositories\Interfaces\IBaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

abstract class BaseRepository implements IBaseRepository
{
    protected Model $model;

    public function getAll(): Collection
    {
        return $this->model->all();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->paginate($perPage);
    }

    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }

    public function first(array $conditions): ?Model
    {
        return $this->model->where($conditions)->first();
    }

    public function getByConditions(array $conditions): Collection
    {
        return $this->model->where($conditions)->get();
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        return $this->model->where('id', $id)->update($data) > 0;
    }

    public function delete(int $id): bool
    {
        return $this->model->where('id', $id)->delete() > 0;
    }

    public function insertMany(array $data): bool
    {
        return $this->model->insert($data);
    }
}
