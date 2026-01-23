<?php

namespace App\Policies;

use App\Models\User;

class PositionPolicy
{
    /**
     * System-level bypass
     * ADMIN and PMO have full access everywhere
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasSystemPosition('ADMIN')) {
            return true;
        }

        return null;
    }

    /**
     * View list of positions
     * Anyone can view
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * View a single position
     * Anyone can view
     */
    public function view(User $user): bool
    {
        return true;
    }

    /**
     * Create a position
     * Only ADMIN/PMO allowed (handled by before)
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Update a position
     * Only ADMIN/PMO allowed (handled by before)
     */
    public function update(User $user): bool
    {
        return false;
    }

    /**
     * Delete a position
     * Only ADMIN/PMO allowed (handled by before)
     */
    public function delete(User $user): bool
    {
        return false;
    }
}
