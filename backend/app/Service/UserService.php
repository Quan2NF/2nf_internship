<?php

namespace App\Service;

use App\Contracts\Service\UserServiceInterface;
use App\Data\Common\KeyOnlyData;
use App\Data\Response\ApiResponseData;
use App\Data\User\AssignPositionsToUserData;
use App\Data\User\AssignRolesToUserData;
use App\Data\User\UserData;
use App\Data\User\UserListFilterData;

class UserService extends BaseService implements UserServiceInterface
{
    public function getFilteredList(UserListFilterData $data): ApiResponseData
    {
        throw new \Exception('Not implemented');
    }

    public function assignPositions(AssignPositionsToUserData $data): ApiResponseData
    {
        throw new \Exception('Not implemented');
    }

    public function getPositions(KeyOnlyData $data): ApiResponseData
    {
        throw new \Exception('Not implemented');
    }
}