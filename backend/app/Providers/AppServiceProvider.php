<?php

namespace App\Providers;

#use Illuminate\Support\ServiceProvider;
use App\Services\Interfaces\IAuthService;
use App\Services\Implementations\AuthService;
use App\Repositories\Interfaces\IUserRepository;
use App\Repositories\Implementations\UserRepository;
use App\Repositories\Interfaces\IProjectRepository;
use App\Repositories\Implementations\ProjectRepository;
use App\Repositories\Interfaces\IIssueRepository;
use App\Repositories\Implementations\IssueRepository;
use App\Services\Interfaces\IProjectService;
use App\Services\Implementations\ProjectService;
use App\Services\Interfaces\IIssueService;
use App\Services\Implementations\IssueService;


use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Project;
use App\Policies\ProjectPolicy;
use App\Models\Issue;
use App\Policies\IssuePolicy;


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
        $this->app->bind(IIssueRepository::class, IssueRepository::class);

        // Service
        $this->app->bind(IProjectService::class, ProjectService::class);
        $this->app->bind(IIssueService::class, IssueService::class);
    }

    
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Project::class, ProjectPolicy::class);
        Gate::policy(Issue::class, IssuePolicy::class);
        /*
        |--------------------------------------------------------------------------
        | Gate: System level
        |--------------------------------------------------------------------------
        */
        Gate::define('admin', fn (User $user) =>
            $user->role === 'admin'
        );
        /*
        |--------------------------------------------------------------------------
        | Gate: Project management
        |--------------------------------------------------------------------------
        */
        Gate::define('manage-project', fn (User $user) =>
            in_array($user->role, [
                'admin',
                'pm',
                'pmo',
            ])
        );
        /*
        |--------------------------------------------------------------------------
        | Gate: Project execution
        |--------------------------------------------------------------------------
        */
        Gate::define('work-on-project', fn (User $user) =>
            in_array($user->role, [
                'admin',
                'pm',
                'pmo',
                'ba',
                'dev_backend',
                'dev_frontend',
                'qa',
                'tester',
                'comtor',
            ])
        );
    }
}
