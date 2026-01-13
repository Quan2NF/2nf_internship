<?php

namespace App\Repositories\Contracts;

use App\Models\Issue;
use Illuminate\Database\Eloquent\Collection;

interface IssueRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?Issue;
    public function create(array $data): Issue;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function forceDelete(int $id): bool;
    public function restore(int $id): bool;
    public function findByProject(int $projectId): Collection;
    public function findByAssignee(int $userId): Collection;
    public function findByReporter(int $userId): Collection;
    public function findOpen(): Collection;
    public function findClosed(): Collection;
    public function findByStatus(int $status): Collection;
    public function findByPriority(int $priority): Collection;
    public function findByType(int $type): Collection;
    public function findOverdue(): Collection;
    public function findActive(): Collection;
}
