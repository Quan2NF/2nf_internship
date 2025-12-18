<?php

namespace App\Service\Interfaces;

use App\Http\Responses\ApiResponse;

use App\Data\Role\GetListRoleRequestData;
use App\Data\Role\CreateRoleRequestData;
use App\Data\Role\EditRoleRequestData;
use App\Data\Role\DeleteRoleRequestData;

interface RoleServiceInterface
{
    public function GetList(GetListRoleRequestData $data): ApiResponse;

    public function Create(CreateRoleRequestData $data): ApiResponse;

    public function Edit(EditRoleRequestData $data): ApiResponse;

    public function Delete(DeleteRoleRequestData $data): ApiResponse;
}