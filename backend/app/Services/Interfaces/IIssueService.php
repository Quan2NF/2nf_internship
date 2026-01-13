<?php

namespace App\Services\Interfaces;

interface IIssueService
{
    public function getByProject(int $projectId);
    public function create(array $data);
    public function delete(int $id): void;
}
