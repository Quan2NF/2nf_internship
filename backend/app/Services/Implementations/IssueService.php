<?php

namespace App\Services\Implementations;

use App\Repositories\Interfaces\IIssueRepository;
use App\Services\Interfaces\IIssueService;

class IssueService implements IIssueService
{
    public function __construct(
        private readonly IIssueRepository $issueRepository
    ) {}

    public function getByProject(int $projectId)
    {
        return $this->issueRepository->findByProject($projectId);
    }

    public function create(array $data)
    {
        return $this->issueRepository->create($data);
    }

    public function delete(int $id): void
    {
        $this->issueRepository->delete($id); // soft delete
    }
}
