<?php

namespace App\Data;

use App\Http\Requests\ResetPasswordRequest;
use Spatie\LaravelData\Data;

class ResetPasswordData extends Data
{
    public function __construct(
        public string $email,
        public string $token,
        public string $password,
    ) {}

    public static function fromRequest(ResetPasswordRequest $request): self
    {
        return new self(
            $request->input('email'),
            $request->input('token'),
            $request->input('password'),
        );
    }
}
