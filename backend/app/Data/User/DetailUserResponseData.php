<?php

namespace App\Data\User;

use Spatie\LaravelData\Data;
use App\Enums\User\UserGender;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

class DetailUserResponseData extends Data
{
    public function __construct(
        public int $id,
        public string $employee_code,
        public string $name,
        public string $email,

        public ?string $phone_number = null,

        #[WithCast(DateTimeInterfaceCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d')]
        public ?\DateTimeInterface $birthday = null,

        public ?UserGender $gender = null,

        #[WithCast(DateTimeInterfaceCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d')]
        public ?\DateTimeInterface $join_date = null,

        #[WithCast(DateTimeInterfaceCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d')]
        public ?\DateTimeInterface $resign_date = null,

        public ?string $avatar = null,
        public bool $is_active = true,
    ) {}
}
