<?php

namespace App\Repositories\Interfaces;

use App\Models\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ITaskRepository extends IBaseRepository
{
    public function paginateByFilter(array $filter, int $perPage = 15): LengthAwarePaginator;

    public function findById(int $id): ?Task;

    public function createTask(array $data): Task;

    public function updateTask(int $id, array $data): Task;

    public function deleteTask(int $id): void;

    public function addComment(int $taskId, int $userId, string $content): void;

    public function addLog(int $taskId, int $userId, string $field, ?string $oldValue, ?string $newValue): void;

    public function getLogs(int $taskId): array;
}
