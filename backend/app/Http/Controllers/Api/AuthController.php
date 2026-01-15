<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Data\Authentication\LoginRequestData;
use App\Contracts\Service\AuthenticationServiceInterface;

class AuthController extends Controller
{
    public function __construct(
        protected AuthenticationServiceInterface $authService
    ){}

    public function login(LoginRequest $request)
    {
        return $this->authService->login(
            new LoginRequestData(
                $request->email,
                $request->password
            )
        );
    }
}
