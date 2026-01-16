<?php

namespace App\Data\Position;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\WithCast;
use App\Enums\Position\PositionScope;

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
        public string $code,
        public string $name,

        #[WithCast('enum:App\Enums\Position\PositionScope')]
        public PositionScope $scope,
    ) {}
}
