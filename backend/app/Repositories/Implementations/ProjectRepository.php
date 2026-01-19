<?php

namespace App\Repositories\Implementations;

use App\Models\Project;
use App\Repositories\Interfaces\IProjectRepository;
use Illuminate\Database\Eloquent\Collection;

class ProjectRepository extends BaseRepository implements IProjectRepository
{
    public function __construct(Project $model)
    {
        $this->model = $model; // ✅ đúng với BaseRepository của bạn
    }

    public function findByUser(int $userId): Collection
    {
        return $this->model
            ->where('user_id', $userId)
            ->get();
    }
}
