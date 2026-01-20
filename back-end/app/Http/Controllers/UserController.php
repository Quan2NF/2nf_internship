<?php

namespace App\Http\Controllers;

use App\Data\CreateUserData;
use App\Data\AssignPositionsData;
use App\Data\ListUsersData;
use App\Data\UpdateUserData;
use App\Exceptions\BusinessException;
use App\Http\Requests\AssignPositionsRequest;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\ListUsersRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\UserServiceInterface;

class UserController extends Controller
{
    public function __construct(
        private readonly UserServiceInterface $userService
    ) {}

    // API05/06 - List users (admin only)
    public function index(ListUsersRequest $request)
    {
        $filter = ListUsersData::fromArray($request->validated());

        try {
            $result = $this->userService->listUsers($filter);

            return response()->json([
                'data' => $result,
            ], 200);
        } catch (BusinessException $e) {
            return response()->json([
                'statusCode' => $e->getStatusCode(),
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }
    }

    // API07 - Create user (admin only)
    public function store(CreateUserRequest $request)
    {
        $data = CreateUserData::fromArray($request->validated());

        try {
            $result = $this->userService->createUser($data);

            return response()->json([
                'data' => $result,
            ], 201);
        } catch (BusinessException $e) {
            return response()->json([
                'statusCode' => $e->getStatusCode(),
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }
    }

    // API08 - Edit user (admin only)
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = UpdateUserData::fromArray($request->validated());

        try {
            $result = $this->userService->updateUser($user->id, $data);

            return response()->json([
                'data' => $result,
            ], 200);
        } catch (BusinessException $e) {
            return response()->json([
                'statusCode' => $e->getStatusCode(),
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }
    }

    // API09 - Delete user (admin only)
    public function destroy(User $user)
    {
        try {
            $this->userService->deleteUser($user->id);

            return response()->json([
                'message' => 'User deleted successfully',
            ], 200);
        } catch (BusinessException $e) {
            return response()->json([
                'statusCode' => $e->getStatusCode(),
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }
    }

    // API10 - Assign roles (positions) to user (admin only)
    public function assignPositions(AssignPositionsRequest $request, User $user)
    {
        $data = AssignPositionsData::fromArray($request->validated());

        try {
            $result = $this->userService->assignPositions($user->id, $data);

            return response()->json([
                'data' => $result,
            ], 200);
        } catch (BusinessException $e) {
            return response()->json([
                'statusCode' => $e->getStatusCode(),
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }
    }

    // API11 - List of user's roles (positions) (admin only)
    public function listPositions(User $user)
    {
        try {
            $result = $this->userService->listUserPositions($user->id);

            return response()->json([
                'data' => $result,
            ], 200);
        } catch (BusinessException $e) {
            return response()->json([
                'statusCode' => $e->getStatusCode(),
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }
    }
}
