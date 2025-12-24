<?php

namespace App\Repositories\Implements;

use App\Repositories\Interfaces\IBaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

abstract class BaseRepository implements IBaseRepository
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    protected function query(): Builder
    {
        return $this->model->newQuery();
    }

    public function all(array $columns = ['*'], array $with = []) : Collection
    {
          return $this->query()
            ->with($with)
            ->get($columns);
    }

    public function find(int $id, array $with = []) : ?Model
    {
        return $this->query()
            ->with($with)
            ->findOrFail($id);
    }

    public function findBy(array $conditions, array $with = []) : Collection
    {
        $query = $this->query()->with($with);

        foreach ($conditions as $field => $value) {
            $query->where($field, $value);
        }

        return $query->get();
    }

    public function create(array $data) : Model
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data) : Model
    {
        $model = $this->model->findOrFail($id);
        $model->update($data);
        return $model;
    }

    public function delete(int $id) : bool
    {
        $model = $this->model->find($id);

        if (!$model) {
            return false;
        }

        $model->delete();
        return true;
    }

    // ================= TRANSACTION =================

    public function beginTransaction(): void
    {
        DB::beginTransaction();
    }

    public function commit(): void
    {
        DB::commit();
    }

    public function rollBack(): void
    {
        DB::rollBack();
    }
}
