<?php

namespace App\Data\Common;

use Spatie\LaravelData\Data;

class KeyOnlyData extends Data
{
    public function __construct(
        public int $id,
    ) {}
}
