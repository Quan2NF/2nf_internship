<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Project;
use App\Models\Issue;
use App\Policies\UserPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\IssuePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for your application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Project::class => ProjectPolicy::class,
        Issue::class => IssuePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Define Gates for role-based permissions

        // User Management Gates
        Gate::define('manage-users', function (User $user) {
            return $user->hasRole(['admin', 'manager']);
        });

        Gate::define('view-users', function (User $user) {
            return true;
        });

        // Project Management Gates
        Gate::define('manage-projects', function (User $user) {
            return $user->hasRole(['admin', 'manager']);
        });

        Gate::define('create-projects', function (User $user) {
            return true;
        });

        Gate::define('view-all-projects', function (User $user) {
            return $user->hasRole(['admin', 'manager']);
        });

        // Issue Management Gates
        Gate::define('manage-issues', function (User $user) {
            return $user->hasRole(['admin', 'manager']);
        });

        Gate::define('assign-issues', function (User $user) {
            return $user->hasRole(['admin', 'manager']);
        });

        Gate::define('view-issues', function (User $user) {
            return true;
        });

        // Admin Only Gates
        Gate::define('admin-only', function (User $user) {
            return $user->hasRole(['admin']);
        });

        // Register policies
        $this->registerPolicies();
    }
}

