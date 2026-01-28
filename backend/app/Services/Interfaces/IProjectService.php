<?php

namespace App\Services\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Project;

interface IProjectService
{
    public function listProjects(int $actorId, array $filters = [], int $perPage = 15): LengthAwarePaginator;

    public function create(array $data, int $actorId): Project;

    public function update(int $projectId, array $data, int $actorId): bool;

    public function delete(int $projectId): void;

    // API21
    public function assignPm(int $projectId, int $pmUserId, int $actorId): bool;

    // API22
    public function assignMembers(int $projectId, array $members, int $actorId): bool;

    // API23 + API24
    public function getSetting(int $projectId): array;
    public function updateSetting(int $projectId, string $content, int $actorId): bool;

    // API25 + API26
    public function getSchedule(int $projectId): array;
    public function updateSchedule(int $projectId, array $versions, int $actorId): bool;
}
