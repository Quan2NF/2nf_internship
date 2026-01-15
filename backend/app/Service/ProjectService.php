<?php

namespace App\Service;

use App\Contracts\Service\ProjectServiceInterface;
use App\Data\Common\KeyOnlyData;
use App\Data\Project\AssignMembersToProjectData;
use App\Data\Project\AssignPMData;
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

    public function assignPM(AssignPMData $data): ApiResponseData
    {
        throw new \Exception('Not implemented');
    }

    public function assignMembers(AssignMembersToProjectData $data): ApiResponseData
    {
        throw new \Exception('Not implemented');
    }

    public function getSetting(KeyOnlyData $data): ApiResponseData
    {
        throw new \Exception('Not implemented');
    }

    public function updateSetting(ProjectSettingData $data): ApiResponseData
    {
        throw new \Exception('Not implemented');
    }

    public function getScheduleAndInfo(KeyOnlyData $data): ApiResponseData
    {
        throw new \Exception('Not implemented');
    }

    public function updateScheduleAndInfo(ProjectScheduleAndInfoData $data): ApiResponseData
    {
        throw new \Exception('Not implemented');
    }
}