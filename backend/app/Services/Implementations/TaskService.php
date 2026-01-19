<?php

namespace App\Services\Implementations;

use App\Models\Task;
use App\Repositories\Interfaces\ITaskRepository;
use App\Services\Interfaces\ITaskService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TaskService implements ITaskService
{
    public function __construct(
        private readonly ITaskRepository $taskRepository
    ) {}

    public function getByProject(int $projectId): Collection
    {
        return $this->taskRepository->findByProject($projectId);
    }

    public function paginateByProject(int $projectId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->taskRepository->paginateByProject($projectId, $perPage);
    }

    public function find(int $id): ?Task
    {
        $task = $this->taskRepository->find($id);
        return $task instanceof Task ? $task : null;
    }

    public function create(array $data): Task
    {
        $task = $this->taskRepository->create($data);
        return $task instanceof Task ? $task : throw new \RuntimeException('Failed to create task.');
    }

    public function update(int $id, array $data): bool
    {
        return $this->taskRepository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->taskRepository->delete($id);
    }
}
