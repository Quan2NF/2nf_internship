<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Allow listing for admins/PMO; controllers may further filter by project membership
        return $user->isAdmin() || $user->isPMO();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Task $task): bool
    {
        if ($user->isAdmin() || $user->isPMO()) return true;
        if ($user->id === $task->assigned_to) return true;
        if ($user->id === $task->created_by) return true;
        if ($user->isPmOfProject($task->project_id)) return true;
        if ($user->isMemberOfProject($task->project_id)) return true;
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Allow creation for admins/PMO and project members/PMs. For project-scoped checks,
        // controllers can call the policy with the project id as an additional argument.
        return $user->isAdmin() || $user->isPMO() || $user->isPM();
    }
    
    
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Task $task): bool
    {
        if ($user->isAdmin() || $user->isPMO()) return true;
        if ($user->id === $task->assigned_to) return true;
        if ($user->id === $task->created_by) return true;
        if ($user->isPmOfProject($task->project_id)) return true;
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Task $task): bool
    {
        if ($user->isAdmin() || $user->isPMO()) return true;
        if ($user->id === $task->created_by) return true;
        if ($user->isPmOfProject($task->project_id)) return true;
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Task $task): bool
    {
        return $user->isAdmin() || $user->isPMO();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Task $task): bool
    {
        return $user->isAdmin();
    }
}
