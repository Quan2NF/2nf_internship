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
     * Determine whether the user can view the model.
     */
    public function view(User $user, Issue $issue): bool
    {
        return $user->id === $issue->reported_by
            || $user->id === $issue->assigned_to
            || $user->id === $issue->project->created_by
            || $user->hasRole(['admin', 'manager']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Issue $issue): bool
    {
        return $user->id === $issue->reported_by
            || $user->id === $issue->assigned_to
            || $user->id === $issue->project->created_by
            || $user->hasRole(['admin', 'manager']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Issue $issue): bool
    {
        return $user->id === $issue->reported_by
            || $user->id === $issue->project->created_by
            || $user->hasRole(['admin', 'manager']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Issue $issue): bool
    {
        return $user->hasRole(['admin', 'manager']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Issue $issue): bool
    {
        return $user->hasRole(['admin']);
    }

    /**
     * Determine whether the user can assign the issue.
     */
    public function assign(User $user, Issue $issue): bool
    {
        return $user->id === $issue->reported_by
            || $user->id === $issue->project->created_by
            || $user->hasRole(['admin', 'manager']);
    }

    /**
     * Determine whether the user can change issue status.
     */
    public function changeStatus(User $user, Issue $issue): bool
    {
        return $user->id === $issue->assigned_to
            || $user->id === $issue->reported_by
            || $user->id === $issue->project->created_by
            || $user->hasRole(['admin', 'manager']);
    }
}

