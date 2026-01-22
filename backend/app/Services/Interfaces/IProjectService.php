<?php

namespace App\Services\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Project;

interface IProjectService
{
    public function listProjects(int $actorId, array $filters = [], int $perPage = 15): LengthAwarePaginator;

    public function create(array $data, int $actorId): Project;

    public function update(int $projectId, array $data, int $actorId): bool;

    public function delete(int $projectId): void;
}
