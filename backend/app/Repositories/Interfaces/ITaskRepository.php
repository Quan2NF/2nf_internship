<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ITaskRepository extends IBaseRepository
{
    /**
     * Get tasks by project id.
     *
     * @param int $projectId
     * @return Collection
     */
    public function findByProject(int $projectId): Collection;

    /**
     * Paginate tasks by project id (optional).
     *
     * @param int $projectId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginateByProject(int $projectId, int $perPage = 15): LengthAwarePaginator;
}
