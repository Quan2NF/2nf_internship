<?php

namespace App\Data\User;

use Spatie\LaravelData\Data;

/**
 * Data used to assign positions to a user.
 */
class AssignPositionsToUserData extends Data
{
    /**
     * @param int[] $position_ids
     */
    public function __construct(
        public array $position_ids,
    ) {}
}
