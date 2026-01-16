<?php

namespace App\Http\Controllers\Api;

use App\Data\User\UserData;
use Illuminate\Http\Request;
use App\Data\User\CreateUserData;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateUserRequest;
use App\Contracts\Service\UserServiceInterface;

class UserController extends Controller
{
    public function __construct(
        protected UserServiceInterface $userService
    ){}

    public function create(CreateUserRequest $request)
    {
        return $this->userService->create(
            CreateUserData::from($request->validated())
        );
    }
}
