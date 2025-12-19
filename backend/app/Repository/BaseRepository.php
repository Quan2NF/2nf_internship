<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Repository\Interfaces\BaseRepositoryInterface;

abstract class BaseRepository implements BaseRepositoryInterface
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

    public function all(array $with = []): Collection
    {
        return $this->query()
            ->with($with)
            ->get();
    }

    public function findByCondition(callable $condition, array $with = []): Collection
    {
        $query = $this->query()->with($with);
        $condition($query);
        return $query->get();
    }

    public function anyByCondition(callable $condition): bool
    {
        $query = $this->query();
        $condition($query);
        return $query->exists();
    }

    public function getById(int $id, array $with = []): ?Model
    {
        return $this->query()
            ->with($with)
            ->find($id);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function createMany(array $data): Collection
    {
        return DB::transaction(function () use ($data) {
            return collect($data)->map(
                fn ($item) => $this->create($item)
            );
        });
    }

    public function update(int $id, array $data): Model
    {
        $model = $this->model->findOrFail($id);
        $model->update($data);
        return $model;
    }

    public function softDelete(int $id): bool
    {
        $model = $this->model->find($id);

        if (! $model) {
            return false;
        }

        $model->delete();
        return true;
    }
    
    public function hardDelete(int $id): bool
    {
        $model = $this->model
            ->withTrashed()
            ->find($id);

        if (! $model) {
            return false;
        }

        $model->forceDelete();
        return true;
    }

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