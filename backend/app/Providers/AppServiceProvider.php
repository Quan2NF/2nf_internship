<?php

namespace App\Providers;

#use Illuminate\Support\ServiceProvider;
use App\Services\Interfaces\IAuthService;
use App\Services\Implementations\AuthService;
use App\Repositories\Interfaces\IUserRepository;
use App\Repositories\Implementations\UserRepository;


use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(IAuthService::class, AuthService::class);
        $this->app->bind(IUserRepository::class, UserRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
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
