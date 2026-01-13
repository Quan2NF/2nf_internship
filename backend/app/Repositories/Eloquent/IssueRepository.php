<?php

namespace App\Repositories\Eloquent;

use App\Models\Issue;
use App\Repositories\Contracts\IssueRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class IssueRepository implements IssueRepositoryInterface
{
    protected Issue $model;

    public function __construct(Issue $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function find(int $id): ?Issue
    {
        return $this->model->find($id);
    }

    public function create(array $data): Issue
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $issue = $this->find($id);
        if (!$issue) {
            return false;
        }
        return $issue->update($data);
    }

    public function delete(int $id): bool
    {
        $issue = $this->find($id);
        if (!$issue) {
            return false;
        }
        return $issue->delete();
    }

    public function forceDelete(int $id): bool
    {
        $issue = $this->model->withTrashed()->find($id);
        if (!$issue) {
            return false;
        }
        return $issue->forceDelete();
    }

    public function restore(int $id): bool
    {
        $issue = $this->model->withTrashed()->find($id);
        if (!$issue) {
            return false;
        }
        return $issue->restore();
    }

    public function getTrashed(): Collection
    {
        return $this->model->onlyTrashed()->get();
    }

    public function findByProject(int $projectId): Collection
    {
        return $this->model->where('project_id', $projectId)->get();
    }

    public function findByAssignee(int $userId): Collection
    {
        return $this->model->where('assigned_to', $userId)->get();
    }

    public function findByReporter(int $userId): Collection
    {
        return $this->model->where('reported_by', $userId)->get();
    }

    public function findOpen(): Collection
    {
        return $this->model->open()->get();
    }

    public function findClosed(): Collection
    {
        return $this->model->closed()->get();
    }

    public function findByStatus(int $status): Collection
    {
        return $this->model->byStatus($status)->get();
    }

    public function findByPriority(int $priority): Collection
    {
        return $this->model->byPriority($priority)->get();
    }

    public function findByType(int $type): Collection
    {
        return $this->model->byType($type)->get();
    }

    public function findOverdue(): Collection
    {
        return $this->model->where('due_date', '<', now()->toDateString())
            ->whereNotIn('status', [Issue::STATUS_RESOLVED, Issue::STATUS_CLOSED])
            ->get();
    }

    public function findActive(): Collection
    {
        return $this->model->active()->get();
    }
}
