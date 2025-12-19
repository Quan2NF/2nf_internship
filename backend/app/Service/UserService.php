<?php

namespace App\Service;

use App\Service\Interfaces\UserServiceInterface;
use App\Http\Responses\ApiResponse;
use App\Data\User\AssignRolesToUserData;
use App\Data\User\UserData;
use App\Data\User\UserListFilterData;

class UserService extends BaseService implements UserServiceInterface
{
    public function getFilteredList(UserListFilterData $data): ApiResponse
    {
        throw new \Exception('Not implemented');
    }

    public function assignRoles(AssignRolesToUserData $data): ApiResponse
    {
        throw new \Exception('Not implemented');
    }

    public function getRoles(UserData $data): ApiResponse
    {
        throw new \Exception('Not implemented');
    }
}