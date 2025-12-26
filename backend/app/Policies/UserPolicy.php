<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * System-level bypass
     * ADMIN has full access everywhere in user management
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->positions()->where('code', 'ADMIN')->exists()) {
            return true;
        }

        return null;
    }

    /**
     * List of users
     * ADMIN only (handled by before)
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * View user detail
     * ADMIN only (handled by before)
     */
    public function view(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Create new user
     * ADMIN only (handled by before)
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Edit user
     * ADMIN only (handled by before)
     */
    public function update(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Delete user
     * ADMIN only (handled by before)
     */
    public function delete(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Assign positions to user
     * ADMIN only (handled by before)
     */
    public function assignPositions(User $user, User $model): bool
    {
        return false;
    }

    /**
     * List user's positions
     * ADMIN only (handled by before)
     */
    public function getPositions(User $user, User $model): bool
    {
        return false;
    }
}
