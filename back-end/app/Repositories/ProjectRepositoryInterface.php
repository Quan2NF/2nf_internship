<?php

namespace App\Repositories;

use App\Data\CreateProjectData;
use App\Data\ListProjectsData;
use App\Data\UpdateProjectData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProjectRepositoryInterface
{
    public function paginateProjects(ListProjectsData $filter): LengthAwarePaginator;

    public function findById(int $id);

    public function createProject(CreateProjectData $data): array;

    public function updateProject(int $id, UpdateProjectData $data): array;

    public function deleteProject(int $id): bool;

    public function assignPm(int $projectId, int $pmId): bool;

    public function assignMembers(int $projectId, array $memberIds): bool;

    public function setSettings(int $projectId, array $settings): array;

    public function updateSettings(int $projectId, array $settings): array;

    public function getSchedule(int $projectId): array;

    public function updateSchedule(int $projectId, array $schedule): array;
}
