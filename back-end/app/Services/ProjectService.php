<?php

namespace App\Services;

use App\Data\CreateProjectData;
use App\Data\ListProjectsData;
use App\Data\UpdateProjectData;
use App\Repositories\ProjectRepositoryInterface;

class ProjectService implements ProjectServiceInterface
{
    public function __construct(private readonly ProjectRepositoryInterface $repo) {}

    public function listProjects(ListProjectsData $filter)
    {
        return $this->repo->paginateProjects($filter);
    }

    public function createProject(CreateProjectData $data)
    {
        return $this->repo->createProject($data);
    }

    public function updateProject(int $projectId, UpdateProjectData $data)
    {
        return $this->repo->updateProject($projectId, $data);
    }

    public function deleteProject(int $projectId): bool
    {
        return $this->repo->deleteProject($projectId);
    }

    public function assignPm(int $projectId, int $pmId): bool
    {
        return $this->repo->assignPm($projectId, $pmId);
    }

    public function assignMembers(int $projectId, array $memberIds): bool
    {
        return $this->repo->assignMembers($projectId, $memberIds);
    }

    public function setSettings(int $projectId, array $settings): array
    {
        return $this->repo->setSettings($projectId, $settings);
    }

    public function updateSettings(int $projectId, array $settings): array
    {
        return $this->repo->updateSettings($projectId, $settings);
    }

    public function getSchedule(int $projectId): array
    {
        return $this->repo->getSchedule($projectId);
    }

    public function updateSchedule(int $projectId, array $schedule): array
    {
        return $this->repo->updateSchedule($projectId, $schedule);
    }
}
