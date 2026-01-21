<?php

namespace App\Contracts\Service;

use App\Models\Project;
use App\Data\Common\KeyOnlyData;
use App\Data\Project\AssignPMData;
use App\Data\Project\ProjectSchedule;
use App\Data\Response\ApiResponseData;
use App\Data\Project\ProjectRequestData;
use App\Data\Project\ProjectSettingsData;
use App\Data\Project\ProjectListFilterData;
use App\Data\Project\AssignMembersToProjectData;

interface ProjectServiceInterface
{
    public function getFilteredList(ProjectListFilterData $data): ApiResponseData;

    public function create(ProjectRequestData $data): ApiResponseData;

    public function view(Project $project): ApiResponseData;

    public function update(Project $project, ProjectRequestData $data): ApiResponseData;

    public function delete(Project $project): ApiResponseData;

    public function getPM(Project $project): ApiResponseData;

    public function getMembers(Project $project): ApiResponseData;
    
    public function assignPM(Project $project, AssignPMData $data): ApiResponseData;

    public function assignMembers(Project $project, AssignMembersToProjectData $data): ApiResponseData;

    public function getSettings(KeyOnlyData $data): ApiResponseData;

    public function updateSettings(ProjectSettingsData $data): ApiResponseData;

    public function getSchedule(KeyOnlyData $data): ApiResponseData;

    public function updateSchedule(ProjectSchedule $data): ApiResponseData;
}