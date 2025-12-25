<?php

namespace App\Data\Auth;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\In;

class RegisterData extends Data
{
    public function __construct(
        #[Required]
        public string $employee_code,

        #[Required]
        public string $name,

        #[Required, Email]
        public string $email,

        #[Required, Min(6)]
        public string $password,

        public ?string $phone_number = null,

        public ?string $birthday = null,

        #[In([1, 2, 3])]
        public ?int $gender = null,

        public ?string $join_date = null,

        public ?string $avatar = null,
    ) {}
}
