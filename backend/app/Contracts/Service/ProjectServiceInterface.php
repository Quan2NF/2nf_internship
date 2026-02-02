<?php

namespace App\Contracts\Service;

use App\Models\User;
use App\Models\Project;
use App\Data\Project\AssignPMData;
use App\Data\Response\ApiResponseData;
use App\Data\Project\ProjectRequestData;
use App\Data\Project\ProjectScheduleData;
use App\Data\Project\ProjectSettingsData;
use App\Data\Project\ProjectListFilterData;
use App\Data\Project\AssignMembersToProjectData;

interface ProjectServiceInterface
{
    public function getFilteredList(User $user, ProjectListFilterData $data): ApiResponseData;

    public function create(ProjectRequestData $data): ApiResponseData;

    public function view(Project $project): ApiResponseData;

    public function update(Project $project, ProjectRequestData $data): ApiResponseData;

    public function delete(Project $project): ApiResponseData;

    public function getPM(Project $project): ApiResponseData;

    public function getMembers(Project $project): ApiResponseData;
    
    public function assignPM(Project $project, AssignPMData $data): ApiResponseData;

    public function assignMembers(Project $project, AssignMembersToProjectData $data): ApiResponseData;

    public function getSettings(Project $project): ApiResponseData;

    public function updateSettings(Project $project, ProjectSettingsData $data): ApiResponseData;

    public function getSchedule(Project $project): ApiResponseData;

    public function updateSchedule(Project $project, ProjectScheduleData $data): ApiResponseData;
}