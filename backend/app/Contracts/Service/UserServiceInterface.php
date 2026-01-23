<?php

namespace App\Contracts\Service;

use App\Data\Common\KeyOnlyData;
use App\Data\User\UserListFilterData;
use App\Data\Response\ApiResponseData;
use App\Data\User\AssignPositionsToUserData;
use App\Data\User\CreateUserRequestData;
use App\Data\User\UpdateUserRequestData;

interface UserServiceInterface
{
    public function create(CreateUserRequestData $data): ApiResponseData;

    public function update(UpdateUserRequestData $data): ApiResponseData;

    public function getFilteredList(UserListFilterData $data): ApiResponseData;

    public function assignPositions(AssignPositionsToUserData $data): ApiResponseData;
}