<?php

namespace App\Services;

use App\Data\CreateProjectData;
use App\Data\ListProjectsData;
use App\Data\UpdateProjectData;

interface ProjectServiceInterface
{
    public function listProjects(ListProjectsData $filter);

    public function createProject(CreateProjectData $data);

    public function updateProject(int $projectId, UpdateProjectData $data);

    public function deleteProject(int $projectId): bool;

    public function assignPm(int $projectId, int $pmId): bool;

    public function assignMembers(int $projectId, array $memberIds): bool;

    public function setSettings(int $projectId, array $settings): array;

    public function updateSettings(int $projectId, array $settings): array;

    public function getSchedule(int $projectId): array;

    public function updateSchedule(int $projectId, array $schedule): array;
}
