<?php
namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface IBaseRepository {
    public function all(array $columns = ['*'], array $with = []) : Collection;
    public function find(int $id, array $with = []) : ?Model;
    public function findBy(array $conditions, array $with = []) : Collection;
    public function create(array $data) : Model;
    public function update(int $id, array $data) : Model;
    public function delete(int $id) : bool;
    public function beginTransaction(): void;
    public function commit(): void;
    public function rollBack(): void;
}