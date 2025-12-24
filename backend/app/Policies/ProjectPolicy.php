<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Project;

class ProjectPolicy
{
    /**
     * System-level bypass
     * ADMIN and PMO have full access
     */
    public function before(User $user, string $ability): bool|null
    {
        if (
            $user->positions->contains('code', 'ADMIN') ||
            $user->positions->contains('code', 'PMO')
        ) {
            return true;
        }

        return null;
    }

    /**
     * Create new project
     * ADMIN / PMO only (handled by before)
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Edit existing project
     * ADMIN / PMO (before) OR PM of that project
     */
    public function update(User $user, Project $project): bool
    {
        return $project->members()
            ->where('user_id', $user->id)
            ->whereHas('roles', fn ($q) =>
                $q->where('code', 'PM')
            )
            ->exists();
    }
}
