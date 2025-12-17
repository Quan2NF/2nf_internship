<?php

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface
{
    public function all(array $with = []) : Collection;

    public function findByCondition(callable $condition, array $with = []): Collection;

    public function anyByCondition(callable $condition): bool;

    public function getById(int $id, array $with = []): ?Model;

    public function create(array $data): Model;

    public function createMany(array $data): Collection;

    public function update(int $id, array $data): Model;

    public function softDelete(int $id): bool;

    public function hardDelete(int $id): bool;

    public function beginTransaction(): void;
    
    public function commit(): void;

    public function rollBack(): void;
}