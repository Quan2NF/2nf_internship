<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Data\User\UserListFilterData;
use App\Http\Requests\User\ViewUserRequest;
use App\Data\User\AssignPositionsToUserData;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\DeleteUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Contracts\Service\UserServiceInterface;
use App\Data\User\UserData;
use App\Http\Requests\User\AssignPositionsRequest;
use App\Http\Requests\User\GetFilteredUserListRequest;
use App\Http\Requests\User\GetPositionsRequest;

class UserController extends Controller
{
    public function __construct(
        protected UserServiceInterface $userService
    ){}

    public function create(CreateUserRequest $request)
    {
        return $this->userService->create(UserData::from($request->validated()));
    }

    public function view(User $user, ViewUserRequest $request)
    {
        return $this->userService->view($user);
    }

    public function update(User $user, UpdateUserRequest $request)
    {
        return $this->userService->update(
            $user,
            UserData::from([
                ...$request->validated(),
                'employee_code' => $user->employee_code,
                'email' => $user->email,
            ])
        );
    }

    public function delete(User $user, DeleteUserRequest $request)
    {
        return $this->userService->delete($user);
    }

    public function getFilteredList(GetFilteredUserListRequest $request)
    {
        return $this->userService->getFilteredList(UserListFilterData::from($request->validated()));
    }

    public function assignPositions(User $user, AssignPositionsRequest $request)
    {
        return $this->userService->assignPositions($user, AssignPositionsToUserData::from($request->validated()));
    }

    public function getPositions(User $user, GetPositionsRequest $request)
    {
        return $this->userService->getPositions($user);
    }
}
