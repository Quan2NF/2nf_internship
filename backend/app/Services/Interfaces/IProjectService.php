<?php

namespace App\Services\Interfaces;

interface IProjectService
{
    public function getMyProjects(int $userId);
    public function create(array $data, int $userId);
    public function delete(int $id): void;
}
