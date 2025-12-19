<?php

namespace App\Service\Interfaces;

use App\Data\User\AssignRolesToUserData;
use App\Http\Responses\ApiResponse;

use App\Data\User\UserData;
use App\Data\User\UserListFilterData;

interface UserServiceInterface
{
    public function getFilteredList(UserListFilterData $data): ApiResponse;

    public function create(UserData $data): ApiResponse;

    public function edit(UserData $data): ApiResponse;

    public function delete(UserData $data): ApiResponse;

    public function assignRoles(AssignRolesToUserData $data): ApiResponse;

    public function getRoles(UserData $data): ApiResponse;
}