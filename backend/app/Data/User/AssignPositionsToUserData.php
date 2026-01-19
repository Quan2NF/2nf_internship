<?php

namespace App\Data\User;

use Spatie\LaravelData\Data;

/**
 * Data used to assign positions to a user.
 */
class AssignPositionsToUserData extends Data
{
    /**
     * @param int $user_id
     * @param int[] $position_ids
     */
    public function __construct(
        public int $user_id,
        public array $position_ids,
    ) {}
}
