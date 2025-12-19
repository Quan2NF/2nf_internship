<?php

namespace App\Service;

use App\Contracts\Service\ProjectServiceInterface;
use App\Data\Project\AssignMembersToProjectData;

use App\Data\Project\ProjectData;
use App\Data\Project\ProjectListFilterData;
use App\Data\Project\ProjectScheduleAndInfoData;
use App\Data\Project\ProjectSettingData;
use App\Data\Response\ApiResponseData;

class ProjectService extends BaseService implements ProjectServiceInterface
{
    public function getList(ProjectListFilterData $data): ApiResponseData
    {
        throw new \Exception('Not implemented');
    }

    public function assignPM(ProjectData $data): ApiResponseData
    {
        throw new \Exception('Not implemented');
    }

    public function assignMembers(AssignMembersToProjectData $data): ApiResponseData
    {
        throw new \Exception('Not implemented');
    }

    public function getSetting(ProjectSettingData $data): ApiResponseData
    {
        throw new \Exception('Not implemented');
    }

    public function updateSetting(ProjectSettingData $data): ApiResponseData
    {
        throw new \Exception('Not implemented');
    }

    public function getScheduleAndInfo(ProjectScheduleAndInfoData $data): ApiResponseData
    {
        throw new \Exception('Not implemented');
    }

    public function updateScheduleAndInfo(ProjectScheduleAndInfoData $data): ApiResponseData
    {
        throw new \Exception('Not implemented');
    }
}