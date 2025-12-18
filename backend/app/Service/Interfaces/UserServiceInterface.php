<?php

namespace App\Service\Interfaces;

use App\Http\Responses\ApiResponse;

use App\Data\User\GetFilteredListUserRequestData;
use App\Data\User\CreateUserRequestData;
use App\Data\User\EditUserRequestData;
use App\Data\User\DeleteUserRequestData;
use App\Data\User\AssignRolesToUserRequestData;
use App\Data\User\GetUserRolesRequestData;

interface UserServiceInterface
{
    public function GetFilteredList(GetFilteredListUserRequestData $data): ApiResponse;

    public function Create(CreateUserRequestData $data): ApiResponse;

    public function Edit(EditUserRequestData $data): ApiResponse;

    public function Delete(DeleteUserRequestData $data): ApiResponse;

    public function AssignRoles(AssignRolesToUserRequestData $data): ApiResponse;

    public function GetRoles(GetUserRolesRequestData $data): ApiResponse;
}