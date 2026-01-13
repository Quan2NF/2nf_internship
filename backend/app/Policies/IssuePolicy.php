<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Issue;
use Illuminate\Auth\Access\Response;

class IssuePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
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
    public function view(User $user, Issue $issue): bool
    {
        // Admin and Manager can view all issues
        if ($user->isAdmin() || $user->isManager()) {
            return true;
        }

        // Only assigned users or reporter can view
        if ($user->id === $issue->assigned_to || $user->id === $issue->reported_by) {
            return true;
        }

        // Project creator can view
        if ($user->id === $issue->project->created_by) {
            return true;
        }

        // Check if user is assigned to the project
        return $issue->project->users->contains($user->id);
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
    public function update(User $user, Issue $issue): bool
    {
        // Admin and Manager can update
        if ($user->isAdmin() || $user->isManager()) {
            return true;
        }

        // Reporter and assigned user can update
        if ($user->id === $issue->reported_by || $user->id === $issue->assigned_to) {
            return true;
        }

        // Project creator can update
        if ($user->id === $issue->project->created_by) {
            return true;
        }

        // Check if user is assigned to the project
        return $issue->project->users->contains($user->id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Issue $issue): bool
    {
        return $user->id === $issue->reported_by
            || $user->id === $issue->project->created_by
            || $user->isAdmin()
            || $user->isManager();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Issue $issue): bool
    {
        return $user->isAdmin() || $user->isManager();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Issue $issue): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can assign the issue.
     */
    public function assign(User $user, Issue $issue): bool
    {
        return $user->id === $issue->reported_by
            || $user->id === $issue->project->created_by
            || $user->isAdmin()
            || $user->isManager();
    }

    /**
     * Determine whether the user can change issue status.
     */
    public function changeStatus(User $user, Issue $issue): bool
    {
        return $user->id === $issue->assigned_to
            || $user->id === $issue->reported_by
            || $user->id === $issue->project->created_by
            || $user->isAdmin()
            || $user->isManager();
    }
}

