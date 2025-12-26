<?php

namespace App\Contracts\Service;

use App\Data\Common\KeyOnlyData;
use App\Data\Response\ApiResponseData;
use App\Data\User\AssignPositionsToUserData;

use App\Data\User\UserListFilterData;

interface UserServiceInterface extends BaseServiceInterface
{
    public function getFilteredList(UserListFilterData $data): ApiResponseData;

    public function assignPositions(AssignPositionsToUserData $data): ApiResponseData;

    public function getPositions(KeyOnlyData $data): ApiResponseData;
}