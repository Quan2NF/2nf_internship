<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use App\Models\Project;

class TaskPolicy
{
    /**
     * System-level bypass
     * ADMIN and PMO have full access everywhere
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->positions()->whereIn('code', ['ADMIN', 'PMO'])->exists()) {
            return true;
        }

        return null;
    }

    /**
     * Check if user can view a list of tasks (filtered by project)
     */
    public function viewAny(User $user, Project $project): bool
    {
        return $this->isProjectMember($user, $project);
    }

    /**
     * View a single task
     */
    public function view(User $user, Task $task): bool
    {
        $project = $task->project; // make sure project is eager-loaded in service
        return $this->isProjectMember($user, $project);
    }

    /**
     * Create a task
     * PM of project can create
     */
    public function create(User $user, Task $task): bool
    {
        $project = $task->project; // eager-loaded
        return $this->isProjectPM($user, $project);
    }

    /**
     * Update a task
     * PM of project can update
     */
    public function update(User $user, Task $task): bool
    {
        $project = $task->project; // eager-loaded
        return $this->isProjectPM($user, $project);
    }

    /**
     * Delete a task
     * ADMIN / PMO only (handled by before)
     */
    public function delete(User $user, Task $task): bool
    {
        return false;
    }

    /**
     * Post comment to task
     * Member of project can comment
     */
    public function postComment(User $user, Task $task): bool
    {
        $project = $task->project; // eager-loaded
        return $this->isProjectMember($user, $project);
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
