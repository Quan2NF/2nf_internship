<?php

namespace App\Repositories\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Project;

interface IProjectRepository extends IBaseRepository
{
    public function paginateVisibleProjects(
        int $userId,
        bool $canViewAll,
        array $filters = [],
        int $perPage = 15
    ): LengthAwarePaginator;

    public function createProject(array $data): Project;

    public function updateProject(int $id, array $data): bool;
}
