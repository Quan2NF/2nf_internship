<?php

namespace App\Services\Implementations;

use App\Repositories\Interfaces\IProjectRepository;
use App\Services\Interfaces\IProjectService;

class ProjectService implements IProjectService
{
    public function __construct(
        private readonly IProjectRepository $projectRepository
    ) {}

    public function getMyProjects(int $userId)
    {
        return $this->projectRepository->findByUser($userId);
    }

    public function create(array $data, int $userId)
    {
        return $this->projectRepository->create([
            ...$data,
            'user_id' => $userId, 
        ]);
    }

    public function delete(int $id): void
    {
        $this->projectRepository->delete($id); // soft delete
    }
}
