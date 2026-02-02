<?php

namespace App\Contracts\Service;

use App\Models\User;
use App\Data\User\UserData;
use App\Data\User\UserListFilterData;
use App\Data\Response\ApiResponseData;
use App\Data\User\AssignPositionsToUserData;

interface UserServiceInterface
{
    public function create(UserData $data): ApiResponseData;

    public function view(User $user): ApiResponseData;

    public function update(User $user, UserData $data): ApiResponseData;

    public function delete(User $user): ApiResponseData;

    public function getFilteredList(UserListFilterData $data): ApiResponseData;

    public function assignPositions(User $user, AssignPositionsToUserData $data): ApiResponseData;

    public function getPositions(User $user);
}