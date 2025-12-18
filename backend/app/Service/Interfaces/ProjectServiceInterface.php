<?php

namespace App\Service\Interfaces;

use App\Http\Responses\ApiResponse;

use App\Data\Project\GetListProjectRequestData;
use App\Data\Project\CreateProjectRequestData;
use App\Data\Project\EditProjectRequestData;
use App\Data\Project\DeleteProjectRequestData;
use App\Data\Project\AssignPMToProjectRequestData;
use App\Data\Project\AssignMembersToProjectRequestData;
use App\Data\Project\GetSettingProjectRequestData;
use App\Data\Project\UpdateSettingProjectRequestData;
use App\Data\Project\GetScheduleInfoProjectRequestData;
use App\Data\Project\UpdateScheduleInfoProjectRequestData;

interface IssueServiceInterface
{
    public function GetList(GetListProjectRequestData $data): ApiResponse;

    public function Create(CreateProjectRequestData $data): ApiResponse;

    public function Edit(EditProjectRequestData $data): ApiResponse;

    public function Delete(DeleteProjectRequestData $data): ApiResponse;

    public function AssignPM(AssignPMToProjectRequestData $data): ApiResponse;

    public function AssignMembers(AssignMembersToProjectRequestData $data): ApiResponse;

    public function GetSetting(GetSettingProjectRequestData $data): ApiResponse;

    public function UpdateSetting(UpdateSettingProjectRequestData $data): ApiResponse;

    public function GetScheduleInfo(GetScheduleInfoProjectRequestData $data): ApiResponse;

    public function UpdateScheduleInfo(UpdateScheduleInfoProjectRequestData $data): ApiResponse;
}