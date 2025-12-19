<?php

namespace App\Contracts\Service;

use App\Data\Project\AssignMembersToProjectData;
use App\Http\Responses\ApiResponse;

use App\Data\Project\ProjectData;
use App\Data\Project\ProjectListFilterData;
use App\Data\Project\ProjectScheduleAndInfoData;
use App\Data\Project\ProjectSettingData;

/**
 * @extends BaseServiceInterface<ProjectData>
 */
interface ProjectServiceInterface extends BaseServiceInterface
{
    public function getList(ProjectListFilterData $data): ApiResponse;

    public function assignPM(ProjectData $data): ApiResponse;

    public function assignMembers(AssignMembersToProjectData $data): ApiResponse;

    public function getSetting(ProjectSettingData $data): ApiResponse;

    public function updateSetting(ProjectSettingData $data): ApiResponse;

    public function getScheduleAndInfo(ProjectScheduleAndInfoData $data): ApiResponse;

    public function updateScheduleAndInfo(ProjectScheduleAndInfoData $data): ApiResponse;
}