<?php

namespace App\Repositories\Implementations;

use App\Models\Role;
use App\Repositories\Interfaces\IRoleRepository;

class RoleRepository extends BaseRepository implements IRoleRepository
{
    public function __construct(Role $role)
    {
        $this->model = $role;
    }
}
