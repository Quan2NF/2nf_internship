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

        // Admin Gates
        Gate::define('admin-only', function (User $user) {
            return $user->hasRole('admin');
        });

        // User Management Gates
        Gate::define('manage-users', function (User $user) {
            return $user->hasRole(['admin', 'manager']);
        });

        Gate::define('view-users', function (User $user) {
            return $user->hasRole(['admin', 'manager']);
        });

        Gate::define('create-users', function (User $user) {
            return $user->hasRole('admin');
        });

        Gate::define('edit-users', function (User $user) {
            return $user->hasRole('admin');
        });

        Gate::define('delete-users', function (User $user) {
            return $user->hasRole('admin');
        });

        Gate::define('assign-roles', function (User $user) {
            return $user->hasRole('admin');
        });

        // Project Management Gates
        Gate::define('manage-projects', function (User $user) {
            return $user->hasRole(['admin', 'manager']);
        });

        Gate::define('create-projects', function (User $user) {
            return $user->is_active === User::STATUS_ACTIVE;
        });

        Gate::define('view-all-projects', function (User $user) {
            return $user->hasRole(['admin', 'manager']);
        });

        Gate::define('manage-project-members', function (User $user) {
            return $user->hasRole(['admin', 'manager']);
        });

        Gate::define('manage-project-settings', function (User $user) {
            return $user->hasRole(['admin', 'manager']);
        });

        // Issue Management Gates
        Gate::define('manage-issues', function (User $user) {
            return $user->hasRole(['admin', 'manager']);
        });

        Gate::define('view-issues', function (User $user) {
            return true;
        });

        Gate::define('create-issues', function (User $user) {
            return $user->is_active === User::STATUS_ACTIVE;
        });

        Gate::define('assign-issues', function (User $user) {
            return $user->hasRole(['admin', 'manager']);
        });

        Gate::define('manage-issue-comments', function (User $user) {
            return $user->is_active === User::STATUS_ACTIVE;
        });

        // Role Management Gates
        Gate::define('manage-roles', function (User $user) {
            return $user->hasRole('admin');
        });

        // Report & Analytics Gates
        Gate::define('view-reports', function (User $user) {
            return $user->hasRole(['admin', 'manager']);
        });

        Gate::define('export-reports', function (User $user) {
            return $user->hasRole(['admin', 'manager']);
        });

        // Register policies
        $this->registerPolicies();
    }
}

