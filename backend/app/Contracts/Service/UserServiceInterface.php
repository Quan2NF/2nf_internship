<?php

namespace App\Contracts\Service;

use App\Data\Response\ApiResponseData;
use App\Data\User\AssignRolesToUserData;

use App\Data\User\UserData;
use App\Data\User\UserListFilterData;

/**
 * @extends BaseServiceInterface<UserData>
 */
interface UserServiceInterface extends BaseServiceInterface
{
    public function getFilteredList(UserListFilterData $data): ApiResponseData;

    public function assignRoles(AssignRolesToUserData $data): ApiResponseData;

    public function getRoles(UserData $data): ApiResponseData;
}