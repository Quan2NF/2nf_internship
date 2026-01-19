<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Enums\ResponseCode;
use Illuminate\Http\Request;
use App\Http\Responses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Data\User\UserListFilterData;
use App\Data\User\CreateUserRequestData;
use App\Data\User\UpdateUserRequestData;
use App\Data\User\DetailUserResponseData;
use App\Http\Requests\User\ViewUserRequest;
use App\Data\User\AssignPositionsToUserData;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\DeleteUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Contracts\Service\UserServiceInterface;
use App\Http\Requests\User\AssignPositionsRequest;
use App\Http\Requests\User\GetFilteredListRequest;
use App\Http\Requests\User\GetPositionsRequest;

class UserController extends Controller
{
    public function __construct(
        protected UserServiceInterface $userService
    ){}
    
    public function view(User $user, ViewUserRequest $request)
    {
        return ApiResponse::from(ResponseCode::SUCCESS, DetailUserResponseData::from($user));
    }

    public function create(CreateUserRequest $request)
    {
        return $this->userService->create(CreateUserRequestData::from($request->validated()));
    }

    public function update(User $user, UpdateUserRequest $request)
    {
        return $this->userService->update(UpdateUserRequestData::from([
                'id' => $user->id,
                ...$request->validated(),
        ]));
    }

    public function delete(User $user, DeleteUserRequest $request)
    {
        $user->delete();
        return ApiResponse::from(ResponseCode::SUCCESS);
    }

    public function getFilteredList(GetFilteredListRequest $request)
    {
        return $this->userService->getFilteredList(UserListFilterData::from($request->validated()));
    }

    public function assignPositions(User $user, AssignPositionsRequest $request)
    {
        return $this->userService->assignPositions(AssignPositionsToUserData::from([
            'user_id' => $user->id,
            ...$request->validated(),
        ]));
    }

    public function getPositions(User $user, GetPositionsRequest $request)
    {
        return ApiResponse::from(ResponseCode::SUCCESS, $user->positions
            ->map(fn ($position) => [
                'id'         => $position->id,
                'name'       => $position->name,
                'start_date' => $position->pivot->start_date,
                'end_date'   => $position->pivot->end_date,
            ])
        );
    }
}
