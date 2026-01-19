<?php

namespace App\Repositories\Implementations;

use App\Models\Task;
use App\Repositories\Interfaces\ITaskRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TaskRepository extends BaseRepository implements ITaskRepository
{
    /**
     * TaskRepository constructor.
     *
     * @param Task $model
     */
    public function __construct(Task $model)
    {
        // BaseRepository has no constructor in your project
        $this->model = $model;
    }

    public function findByProject(int $projectId): Collection
    {
        return $this->model
            ->where('project_id', $projectId)
            ->orderByDesc('id')
            ->get();
    }

    public function paginateByProject(int $projectId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->where('project_id', $projectId)
            ->orderByDesc('id')
            ->paginate($perPage);
    }
}
