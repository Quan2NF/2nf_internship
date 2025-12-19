<?php

namespace App\Service;

use App\Contracts\Service\UserServiceInterface;
use App\Data\Response\ApiResponseData;
use App\Data\User\AssignRolesToUserData;
use App\Data\User\UserData;
use App\Data\User\UserListFilterData;

class UserService extends BaseService implements UserServiceInterface
{
    public function getFilteredList(UserListFilterData $data): ApiResponseData
    {
        throw new \Exception('Not implemented');
    }

    public function assignRoles(AssignRolesToUserData $data): ApiResponseData
    {
        throw new \Exception('Not implemented');
    }

    public function getRoles(UserData $data): ApiResponseData
    {
        throw new \Exception('Not implemented');
    }
}