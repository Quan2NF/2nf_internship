<?php

namespace App\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository
{
    // Allow subclasses to override the model property with specific type
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get all records
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * Find record by ID
     */
    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * Create a new record
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Update a record by ID
     */
    public function update(int $id, array $data): bool
    {
        $record = $this->find($id);

        if (!$record) {
            return false;
        }

        return $record->update($data);
    }

    /**
     * Delete a record by ID (soft delete)
     */
    public function delete(int $id): bool
    {
        $record = $this->find($id);

        if (!$record) {
            return false;
        }

        return $record->delete();
    }

    /**
     * Force delete a record
     */
    public function forceDelete(int $id): bool
    {
        $record = $this->model->withTrashed()->find($id);

        if (!$record) {
            return false;
        }

        return $record->forceDelete();
    }

    /**
     * Restore a soft-deleted record
     */
    public function restore(int $id): bool
    {
        $record = $this->model->withTrashed()->find($id);

        if (!$record) {
            return false;
        }

        return $record->restore();
    }

    /**
     * Paginate results
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->paginate($perPage);
    }

    /**
     * Get trashed records
     */
    public function getTrashed(): Collection
    {
        return $this->model->onlyTrashed()->get();
    }

    /**
     * Get the underlying query builder
     */
    protected function query()
    {
        return $this->model->query();
    }
}
