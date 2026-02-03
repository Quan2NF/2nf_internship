<?php

namespace App\Providers;
use Illuminate\Auth\Notifications\ResetPassword;


#use Illuminate\Support\ServiceProvider;
use App\Services\Interfaces\IAuthService;
use App\Services\Implementations\AuthService;

use App\Services\Interfaces\IUserService;
use App\Services\Implementations\UserService;
use App\Repositories\Interfaces\IUserRepository;
use App\Repositories\Implementations\UserRepository;

use App\Repositories\Interfaces\IProjectRepository;
use App\Repositories\Implementations\ProjectRepository;
use App\Services\Interfaces\IProjectService;
use App\Services\Implementations\ProjectService;

use App\Repositories\Interfaces\ITaskRepository;
use App\Repositories\Implementations\TaskRepository;
use App\Services\Interfaces\ITaskService;
use App\Services\Implementations\TaskService;

use
 App\Repositories\Interfaces\IRoleRepository;
use App\Repositories\Implementations\RoleRepository;
use App\Services\Interfaces\IRoleService;
use App\Services\Implementations\RoleService;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Project;
use App\Policies\ProjectPolicy;
use App\Models\Task;
use App\Policies\TaskPolicy;



class AppServiceProvider extends ServiceProvider
{
    
    public function register(): void
    {
        //$this->app->bind(IAuthService::class, AuthService::class);

        // Repository
        $this->app->bind(IProjectRepository::class, ProjectRepository::class);
        $this->app->bind(ITaskRepository::class, TaskRepository::class);
        $this->app->bind(IUserRepository::class, UserRepository::class);
        $this->app->bind(IRoleRepository::class, RoleRepository::class);
        $this->app->bind(ITaskRepository::class, TaskRepository::class);
    

        // Service
        $this->app->bind(IProjectService::class, ProjectService::class);
        $this->app->bind(ITaskService::class, TaskService::class);
        $this->app->bind(IUserService::class, UserService::class);
        $this->app->bind(IAuthService::class, AuthService::class);
        $this->app->bind(IRoleService::class, RoleService::class);
        $this->app->bind(ITaskService::class, TaskService::class);

    }

    
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Project::class, ProjectPolicy::class);
        Gate::policy(Task::class, TaskPolicy::class); 
        ResetPassword::createUrlUsing(function ($notifiable, string $token) {
            $frontend = rtrim(config('app.frontend_url', env('FRONTEND_URL', 'http://localhost:5173')), '/');
            $email = urlencode($notifiable->getEmailForPasswordReset());
            return "{$frontend}/auth/reset-password/{$token}?email={$email}";
        });
    }
}
