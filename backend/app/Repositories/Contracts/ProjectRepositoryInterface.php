<?php

namespace App\Repositories\Contracts;

use App\Models\Project;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

interface ProjectRepositoryInterface
{
    public function all(): Collection;

    public function find(int $id): ?Project;

    public function findByCode(string $code): ?Project;

    public function create(array $data): Project;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    public function forceDelete(int $id): bool;

    public function restore(int $id): bool;

    public function paginate(int $perPage = 15): LengthAwarePaginator;

    public function getActive(): Collection;

    public function getPublic(): Collection;

    public function getByStatus(int $status): Collection;

    public function getTrashed(): Collection;

    public function getByCreator(int $userId): Collection;

    public function search(string $query): Collection;
}
