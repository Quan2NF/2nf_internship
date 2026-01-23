<?php

namespace App\Data\User;

use Spatie\LaravelData\Data;
use App\Enums\User\UserGender;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

/**
 * Filters used when listing users.
 */
class UserListFilterData extends Data
{
    public function __construct(
        public ?string $keyword = null,
        public ?bool $is_active = null,
        public ?UserGender $gender = null,

        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d')]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d')]
        public ?\DateTime $join_from = null,

        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d')]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d')]
        public ?\DateTime $join_to = null,

        public int $page = 1,
        public int $per_page = 20,
    ) {}
}
