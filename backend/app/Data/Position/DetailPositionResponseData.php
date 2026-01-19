<?php

namespace App\Data\Position;

use Spatie\LaravelData\Data;
use App\Enums\Position\PositionScope;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Transformers\EnumTransformer;

class DetailPositionResponseData extends Data
{
    public function __construct(
        public int $id,
        public string $code,
        public string $name,

        #[WithTransformer(EnumTransformer::class)]
        public PositionScope $scope,
    ) {}
}
