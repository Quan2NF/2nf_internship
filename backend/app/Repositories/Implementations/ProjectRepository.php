<?php

namespace App\Repositories\Implementations;

use App\Models\Project;
use App\Repositories\Interfaces\IProjectRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class ProjectRepository extends BaseRepository implements IProjectRepository
{
    public function __construct(Project $project)
    {
        $this->model = $project; 
    }

    public function paginateVisibleProjects(
        int $userId,
        bool $canViewAll,
        array $filters = [],
        int $perPage = 15
    ): LengthAwarePaginator {
        $q = $this->model->newQuery();

        // Filter
        if (!empty($filters['keyword'])) {
            $kw = (string) $filters['keyword'];
            $q->where(function (Builder $sub) use ($kw) {
                $sub->where('code', 'like', "%{$kw}%")
                    ->orWhere('name', 'like', "%{$kw}%");
            });
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $q->where('status', (string) $filters['status']);
        }

        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $q->where('is_active', (int) $filters['is_active']);
        }

        // Rule hiển thị
        if (!$canViewAll) {
            $q->whereExists(function ($sub) use ($userId) {
                $sub->selectRaw(1)
                    ->from('project_members as pm')
                    ->whereColumn('pm.project_id', 'projects.id')
                    ->where('pm.user_id', $userId);
            });
        }

        return $q->orderByDesc('id')->paginate($perPage);
    }

    public function createProject(array $data): Project
    {
        return $this->model->create($data);
    }

    public function updateProject(int $id, array $data): bool
    {
        return $this->model->where('id', $id)->update($data) > 0;
    }
}
