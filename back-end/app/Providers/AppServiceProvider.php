<?php

namespace App\Providers;

use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\RoleRepository;
use App\Repositories\RoleRepositoryInterface;
use App\Services\AuthService;
use App\Services\AuthServiceInterface;
use App\Services\RoleService;
use App\Services\RoleServiceInterface;
use App\Services\UserService;
use App\Services\UserServiceInterface;
use App\Repositories\ProjectRepository;
use App\Repositories\ProjectRepositoryInterface;
use App\Services\ProjectService;
use App\Services\ProjectServiceInterface;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Task;
use App\Policies\TaskPolicy;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(RoleServiceInterface::class, RoleService::class);
        $this->app->bind(ProjectRepositoryInterface::class, ProjectRepository::class);
        $this->app->bind(ProjectServiceInterface::class, ProjectService::class);
        $this->app->bind(\App\Services\TaskServiceInterface::class, \App\Services\TaskService::class);
        $this->app->bind(\App\Repositories\TaskRepositoryInterface::class, \App\Repositories\TaskRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Task policy so authorize() works without a separate AuthServiceProvider
        Gate::policy(Task::class, TaskPolicy::class);

        // Gate to check project-level management (admin, PMO, or PM of the project)
        Gate::define('manage-project', function (User $user, ?int $projectId = null) {
            if ($user->isAdmin() || $user->isPMO()) return true;
            if (is_null($projectId)) return false;
            return $user->isPmOfProject($projectId);
        });

        // Gate to check task-level management (admin, PMO, PM of project, creator, assigned user)
        Gate::define('manage-task', function (User $user, ?Task $task = null) {
            if ($user->isAdmin() || $user->isPMO()) return true;
            if (is_null($task)) return true; // allow creation checks where task is not available
            if ($user->id === $task->assigned_to) return true;
            if ($user->id === $task->created_by) return true;
            if ($user->isPmOfProject($task->project_id)) return true;
            if ($user->isMemberOfProject($task->project_id)) return true;
            return false;
        });
    }
}
