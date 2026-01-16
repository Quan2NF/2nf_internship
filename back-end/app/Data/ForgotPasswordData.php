<?php

namespace App\Data;

use App\Http\Requests\ForgotPasswordRequest;
use Spatie\LaravelData\Data;

class ForgotPasswordData extends Data
{
    public function __construct(
        public string $email,
    ) {}

    public static function fromRequest(ForgotPasswordRequest $request): self
    {
        return new self($request->input('email'));
    }
}