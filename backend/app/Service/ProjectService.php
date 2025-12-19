<?php

namespace App\Service;

use App\Service\Interfaces\ProjectServiceInterface;
use App\Data\Project\AssignMembersToProjectData;
use App\Http\Responses\ApiResponse;

use App\Data\Project\ProjectData;
use App\Data\Project\ProjectListFilterData;
use App\Data\Project\ProjectScheduleAndInfoData;
use App\Data\Project\ProjectSettingData;


class ProjectService extends BaseService implements ProjectServiceInterface
{
    public function getList(ProjectListFilterData $data): ApiResponse
    {
        throw new \Exception('Not implemented');
    }

    public function assignPM(ProjectData $data): ApiResponse
    {
        throw new \Exception('Not implemented');
    }

    public function assignMembers(AssignMembersToProjectData $data): ApiResponse
    {
        throw new \Exception('Not implemented');
    }

    public function getSetting(ProjectSettingData $data): ApiResponse
    {
        throw new \Exception('Not implemented');
    }

    public function updateSetting(ProjectSettingData $data): ApiResponse
    {
        throw new \Exception('Not implemented');
    }

    public function getScheduleAndInfo(ProjectScheduleAndInfoData $data): ApiResponse
    {
        throw new \Exception('Not implemented');
    }

    public function updateScheduleAndInfo(ProjectScheduleAndInfoData $data): ApiResponse
    {
        throw new \Exception('Not implemented');
    }
}