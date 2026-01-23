<?php

namespace App\Data\Position;

use Spatie\LaravelData\Data;
use App\Enums\Position\PositionScope;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Transformers\EnumTransformer;

/**
 * Data Transfer Object representing a Position.
 */
class PositionData extends Data
{
    /**
     * @param string $code Unique position code (e.g. admin, pm, pmo)
     * @param string $name Position display name
     * @param PositionScope $scope Position scope (Project/System)
     */
    public function __construct(
        public ?string $code,
        public ?string $name,

        #[WithTransformer(EnumTransformer::class)]
        public ?PositionScope $scope,
    ) {}
}
