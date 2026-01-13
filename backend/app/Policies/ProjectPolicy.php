<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Project;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view projects
    }

    /**
     * Determine whether the user can view trashed models.
     */
    public function viewTrashed(User $user): bool
    {
        return $user->isAdmin() || $user->isManager();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Project $project): bool
    {
        // Admin and Manager can see all projects
        if ($user->isAdmin() || $user->isManager()) {
            return true;
        }

        // Public projects visible to all users
        if ($project->is_public) {
            return true;
        }

        // Only assigned users can view private projects
        return $project->users->contains($user->id) || $user->id === $project->created_by;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->is_active === User::STATUS_ACTIVE;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): bool
    {
        return $user->id === $project->created_by
            || $user->isAdmin()
            || $user->isManager();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project): bool
    {
        return $user->id === $project->created_by
            || $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Project $project): bool
    {
        return $user->isAdmin() || $user->isManager();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Project $project): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can add members to the project.
     */
    public function addMember(User $user, Project $project): bool
    {
        return $user->id === $project->created_by
            || $user->isAdmin()
            || $user->isManager();
    }

    /**
     * Determine whether the user can remove members from the project.
     */
    public function removeMember(User $user, Project $project): bool
    {
        return $user->id === $project->created_by
            || $user->isAdmin()
            || $user->isManager();
    }

    /**
     * Determine whether the user can manage project settings.
     */
    public function manageSettings(User $user, Project $project): bool
    {
        return $user->id === $project->created_by
            || $user->isAdmin()
            || $user->isManager();
    }

    /**
     * Determine whether the user can view project reports.
     */
    public function viewReports(User $user, Project $project): bool
    {
        return $user->id === $project->created_by
            || $project->users->contains($user->id)
            || $user->isAdmin()
            || $user->isManager();
    }
}

