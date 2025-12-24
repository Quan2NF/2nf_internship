<?php

namespace App\Services\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface IBaseService
{
    public function getAll(array $with = []): Collection;
    
    public function findById(int $id, array $with = []): ?Model;
    
    public function findByConditions(array $conditions, array $with = []): Collection;
    
    public function create(array $data): Model;
    
    public function update(int $id, array $data): Model;
    
    public function delete(int $id): bool;
}