<?php

namespace App\Data\Authentication;

use Spatie\LaravelData\Data;

class AuthenticationData extends Data
{
    public readonly ?string $email;
    public readonly ?string $password;
    public readonly ?string $token;
    public readonly ?string $password_confirmation;

    public function __construct(
        ?string $email = null,
        ?string $password = null,
        ?string $token = null,
        ?string $password_confirmation = null,
    ) {
        $this->email = $email;
        $this->password = $password;
        $this->token = $token;
        $this->password_confirmation = $password_confirmation;
    }

    public static function forLogin(string $email, string $password): self
    {
        return new self(email: $email, password: $password);
    }

    public static function forLogout(): self
    {
        return new self();
    }

    public static function forForgotPassword(string $email): self
    {
        return new self(email: $email);
    }

    public static function forPasswordReset(string $email, string $token, string $password, string $password_confirmation): self
    {
        return new self(
            email: $email,
            token: $token,
            password: $password,
            password_confirmation: $password_confirmation
        );
    }
}
