<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class AssignPositionsData extends Data
{
    /**
     * @param array<int, array{position_id?:int|null, position_code?:string|null, start_date?:string|null, end_date?:string|null}> $positions
     */
    public function __construct(
        public array $positions,
    ) {}

    public static function fromArray(array $input): self
    {
        return new self($input['positions']);
    }
}
