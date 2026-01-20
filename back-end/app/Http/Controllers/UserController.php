<?php

namespace App\Http\Controllers;

use App\Data\ListUsersData;
use App\Exceptions\BusinessException;
use App\Http\Requests\ListUsersRequest;
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
}
