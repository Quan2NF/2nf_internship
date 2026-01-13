<?php
namespace App\Repositories\Eloquent;

use App\Models\Project;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProjectRepository implements ProjectRepositoryInterface
{
    protected Project $model;

    public function __construct(Project $model)
    {
        $this->model = $model;
    }

    // Basic CRUD methods from BaseRepository pattern
    public function all(): Collection
    {
        return $this->model->all();
    }

    public function find(int $id): ?Project
    {
        return $this->model->find($id);
    }

    public function create(array $data): Project
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $project = $this->find($id);
        if (!$project) {
            return false;
        }
        return $project->update($data);
    }

    public function delete(int $id): bool
    {
        $project = $this->find($id);
        if (!$project) {
            return false;
        }
        return $project->delete();
    }

    public function forceDelete(int $id): bool
    {
        $project = $this->model->withTrashed()->find($id);
        if (!$project) {
            return false;
        }
        return $project->forceDelete();
    }

    public function restore(int $id): bool
    {
        $project = $this->model->withTrashed()->find($id);
        if (!$project) {
            return false;
        }
        return $project->restore();
    }

    public function getTrashed(): Collection
    {
        return $this->model->onlyTrashed()->get();
    }

    public function findByCode(string $code): ?Project
    {
        return $this->model->where('code', $code)->first();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->where('is_active', true)
            ->latest()
            ->paginate($perPage);
    }

    public function getActive(): Collection
    {
        return $this->model->active()->get();
    }

    public function getPublic(): Collection
    {
        return $this->model->public()->get();
    }

    public function getByStatus(int $status): Collection
    {
        return $this->model->byStatus($status)->get();
    }

    public function getByCreator(int $userId): Collection
    {
        return $this->model->where('created_by', $userId)->get();
    }

    public function search(string $query): Collection
    {
        return $this->model
            ->where('name', 'like', "%{$query}%")
            ->orWhere('code', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->get();
    }

    /**
     * Get projects visible to a user based on their role
     */
    public function getVisibleToUser(int $userId, bool $includeAdmin = true): Collection
    {
        $user = \App\Models\User::find($userId);

        if (!$user) {
            return collect([]);
        }

        // Admin and Manager see all projects
        if ($includeAdmin && ($user->isAdmin() || $user->isManager())) {
            return $this->all();
        }

        // Regular users see only assigned projects
        return $user->projects()->get();
    }

    /**
     * Get paginated projects visible to a user
     */
    public function paginateVisibleToUser(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        $user = \App\Models\User::find($userId);

        if (!$user) {
            return $this->model->paginate(0);
        }

        // Admin and Manager see all projects
        if ($user->isAdmin() || $user->isManager()) {
            return $this->paginate($perPage);
        }

        // Regular users see only assigned projects
        return $user->projects()->paginate($perPage);    }
}
