<?php

namespace App\Data;

use App\Http\Requests\LoginRequest;
use Spatie\LaravelData\Data;

class LoginData extends Data
{
    public function __construct(
        public string $email,
        public string $password,
    ) {}

    public static function fromRequest(LoginRequest $request): self
    {
        return new self(
            $request->input('email'),
            $request->input('password'),
        );
    }
}
