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
     * @param UserPositionData[] $positions
     */
    public function __construct(
        public int $user_id,
        public array $positions,
    ) {}
}
