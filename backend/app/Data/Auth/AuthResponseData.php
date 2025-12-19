<?php

namespace App\Data\Auth;

use Spatie\LaravelData\Data;
use App\Data\User\UserData;

class AuthResponseData extends Data
{
    public function __construct(
        public string $token,
        public string $token_type,
        public UserData $user,
    ) {}
}
