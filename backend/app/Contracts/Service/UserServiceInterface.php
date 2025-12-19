<?php

namespace App\Contracts\Service;

use App\Data\User\AssignRolesToUserData;
use App\Http\Responses\ApiResponse;

use App\Data\User\UserData;
use App\Data\User\UserListFilterData;

/**
 * @extends BaseServiceInterface<UserData>
 */
interface UserServiceInterface extends BaseServiceInterface
{
    public function getFilteredList(UserListFilterData $data): ApiResponse;

    public function assignRoles(AssignRolesToUserData $data): ApiResponse;

    public function getRoles(UserData $data): ApiResponse;
}