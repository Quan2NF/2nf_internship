<?php

namespace App\Contracts\Service;

use App\Data\Common\KeyOnlyData;
use App\Data\Project\AssignMembersToProjectData;
use App\Data\Project\AssignPMData;
use App\Data\Project\ProjectListFilterData;
use App\Data\Project\ProjectScheduleAndInfoData;
use App\Data\Project\ProjectSettingData;
use App\Data\Response\ApiResponseData;

interface ProjectServiceInterface extends BaseServiceInterface
{
    public function getList(ProjectListFilterData $data): ApiResponseData;

    public function assignPM(AssignPMData $data): ApiResponseData;

    public function assignMembers(AssignMembersToProjectData $data): ApiResponseData;

    public function getSetting(KeyOnlyData $data): ApiResponseData;

    public function updateSetting(ProjectSettingData $data): ApiResponseData;

    public function getScheduleAndInfo(KeyOnlyData $data): ApiResponseData;

    public function updateScheduleAndInfo(ProjectScheduleAndInfoData $data): ApiResponseData;
}