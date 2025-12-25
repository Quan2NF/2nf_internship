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
     * Determine whether the user can view the model.
     */
    public function view(User $user, Project $project): bool
    {
        return $project->is_public
            || $user->id === $project->created_by
            || $project->users->contains($user->id)
            || $user->hasRole(['admin', 'manager']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // All authenticated users can create projects
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): bool
    {
        return $user->id === $project->created_by
            || $user->hasRole(['admin', 'manager']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project): bool
    {
        return $user->id === $project->created_by
            || $user->hasRole(['admin']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Project $project): bool
    {
        return $user->hasRole(['admin', 'manager']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Project $project): bool
    {
        return $user->hasRole(['admin']);
    }

    /**
     * Determine whether the user can add members to the project.
     */
    public function addMember(User $user, Project $project): bool
    {
        return $user->id === $project->created_by
            || $user->hasRole(['admin', 'manager']);
    }

    /**
     * Determine whether the user can remove members from the project.
     */
    public function removeMember(User $user, Project $project): bool
    {
        return $user->id === $project->created_by
            || $user->hasRole(['admin', 'manager']);
    }

    /**
     * Determine whether the user can manage project settings.
     */
    public function manageSettings(User $user, Project $project): bool
    {
        return $user->id === $project->created_by
            || $user->hasRole(['admin', 'manager']);
    }

    /**
     * Determine whether the user can view project reports.
     */
    public function viewReports(User $user, Project $project): bool
    {
        return $user->id === $project->created_by
            || $project->users->contains($user->id)
            || $user->hasRole(['admin', 'manager']);
    }
}

