<?php

namespace App\Services\Interfaces;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ITaskService
{
    public function getByProject(int $projectId): Collection;

    public function paginateByProject(int $projectId, int $perPage = 15): LengthAwarePaginator;

    public function find(int $id): ?Task;

    public function create(array $data): Task;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;
}
