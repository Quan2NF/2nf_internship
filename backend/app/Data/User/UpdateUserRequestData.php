<?php

namespace App\Data\User;

use Spatie\LaravelData\Data;
use App\Enums\User\UserGender;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

class UpdateUserRequestData extends Data
{
    public function __construct(
        public int $id,
        public ?string $name,
        public ?string $phone_number = null,

        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d')]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d')]
        public ?\DateTime $birthday = null,

        public ?UserGender $gender = null,

        public ?string $avatar = null,
    ) {}
}
