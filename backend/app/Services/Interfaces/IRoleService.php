<?php

namespace App\Services\Interfaces;

use Illuminate\Support\Collection;

interface IRoleService
{
    
    public function list(): Collection;

    public function create(array $data);

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;
}
