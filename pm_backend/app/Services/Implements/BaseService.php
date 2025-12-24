<?php

namespace App\Services\Implements;

use App\Services\Interfaces\IBaseService;
use App\Repositories\Interfaces\IBaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Exception;

abstract class BaseService implements IBaseService
{
    protected IBaseRepository $repository;

    public function __construct(IBaseRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll(array $with = []): Collection
    {
        try {
            return $this->repository->all(['*'], $with);
        } catch (Exception $e) {
            Log::error('Error in getAll: ' . $e->getMessage());
            throw $e;
        }
    }

    public function findById(int $id, array $with = []): ?Model
    {
        try {
            return $this->repository->find($id, $with);
        } catch (Exception $e) {
            Log::error('Error in findById: ' . $e->getMessage());
            throw $e;
        }
    }

    public function findByConditions(array $conditions, array $with = []): Collection
    {
        try {
            return $this->repository->findBy($conditions, $with);
        } catch (Exception $e) {
            Log::error('Error in findByConditions: ' . $e->getMessage());
            throw $e;
        }
    }

    public function create(array $data): Model
    {
        try {
            return $this->repository->create($data);
        } catch (Exception $e) {
            Log::error('Error in create: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update(int $id, array $data): Model
    {
        try {
            return $this->repository->update($id, $data);
        } catch (Exception $e) {
            Log::error('Error in update: ' . $e->getMessage());
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        try {
            return $this->repository->delete($id);
        } catch (Exception $e) {
            Log::error('Error in delete: ' . $e->getMessage());
            throw $e;
        }
    }
}