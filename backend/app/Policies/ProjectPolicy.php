<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Project;

class ProjectPolicy
{
    /**
     * System-level bypass
     * ADMIN and PMO have full access everywhere
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->hasAnyPosition(['ADMIN', 'PMO'])) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * ADMIN and PMO only
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * View project
     * ADMIN and PMO (before) or project member
     */
    public function view(User $user, Project $project): bool
    {
        return $this->isProjectMember($user, $project);
    }

    /**
     * Edit existing project
     * ADMIN and PMO (before) or PM of that project
     */
    public function update(User $user, Project $project): bool
    {
        return $this->isProjectPM($user, $project);
    }

    /**
     * ADMIN and PMO only
     */
    public function delete(User $user): bool
    {
        return false;
    }

    /**
     * Assign PM
     * ADMIN and PMO only
     */
    public function assignPM(User $user): bool
    {
        return false;
    }

    /**
     * Assign members
     * ADMIN and PMO or PM of project
     */
    public function assignMembers(User $user, Project $project): bool
    {
        return $this->isProjectPM($user, $project);
    }

    /**
     * Update settings
     * ADMIN and PMO or PM of project
     */
    public function updateSettings(User $user, Project $project): bool
    {
        return $this->isProjectPM($user, $project);
    }

    /**
     * Update schedule and status
     * ADMIN and PMO or PM of project
     */
    public function updateSchedule(User $user, Project $project): bool
    {
        return $this->isProjectPM($user, $project);
    }

    /**
     * Check if user is member of project
     */
    private function isProjectMember(User $user, Project $project): bool
    {
        return $project->projectMembers()
            ->where('user_id', $user->id)
            ->exists();
    }
    
    /**
     * Check if user is PM of project
     */
    private function isProjectPM(User $user, Project $project): bool
    {
        $member = $project->projectMembers()
            ->where('user_id', $user->id)
            ->first();

        return $member?->roles()->where('code', 'PM')->exists() ?? false;
    }
}
