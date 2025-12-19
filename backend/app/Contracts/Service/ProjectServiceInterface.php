<?php

namespace App\Contracts\Service;

use App\Data\Project\AssignMembersToProjectData;

use App\Data\Project\ProjectData;
use App\Data\Project\ProjectListFilterData;
use App\Data\Project\ProjectScheduleAndInfoData;
use App\Data\Project\ProjectSettingData;
use App\Data\Response\ApiResponseData;

/**
 * @extends BaseServiceInterface<ProjectData>
 */
interface ProjectServiceInterface extends BaseServiceInterface
{
    public function getList(ProjectListFilterData $data): ApiResponseData;

    public function assignPM(ProjectData $data): ApiResponseData;

    public function assignMembers(AssignMembersToProjectData $data): ApiResponseData;

    public function getSetting(ProjectSettingData $data): ApiResponseData;

    public function updateSetting(ProjectSettingData $data): ApiResponseData;

    public function getScheduleAndInfo(ProjectScheduleAndInfoData $data): ApiResponseData;

    public function updateScheduleAndInfo(ProjectScheduleAndInfoData $data): ApiResponseData;
}