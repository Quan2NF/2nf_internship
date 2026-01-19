<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface IProjectRepository extends IBaseRepository
{
    public function findByUser(int $userId): Collection;
}
