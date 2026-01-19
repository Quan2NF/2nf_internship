<?php

namespace App\Providers;

#use Illuminate\Support\ServiceProvider;
use App\Services\Interfaces\IAuthService;
use App\Services\Implementations\AuthService;
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




use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Project;
use App\Policies\ProjectPolicy;
use App\Models\Task;
use App\Policies\TaskPolicy;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(IAuthService::class, AuthService::class);
        $this->app->bind(IUserRepository::class, UserRepository::class);

        // Repository
        $this->app->bind(IProjectRepository::class, ProjectRepository::class);
        $this->app->bind(ITaskRepository::class, TaskRepository::class);
    

        // Service
        $this->app->bind(IProjectService::class, ProjectService::class);
        $this->app->bind(ITaskService::class, TaskService::class);
    }

    
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Project::class, ProjectPolicy::class);
        Gate::policy(Task::class, TaskPolicy::class); // thêm cái này
        
    }
}
