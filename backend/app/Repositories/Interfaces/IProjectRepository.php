<?php

namespace App\Repositories\Interfaces;

interface IProjectRepository extends IBaseRepository
{
    public function findByUser(int $userId);

}
