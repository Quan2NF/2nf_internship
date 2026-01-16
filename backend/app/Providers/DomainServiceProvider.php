<?php

namespace App\Providers;

use App\Service\AuthenticationService;
use Illuminate\Support\ServiceProvider;
use App\Contracts\Service\AuthenticationServiceInterface;
use App\Contracts\Service\UserServiceInterface;
use App\Service\UserService;

class DomainServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            AuthenticationServiceInterface::class,
            AuthenticationService::class
        );

        $this->app->bind(
            UserServiceInterface::class,
            UserService::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
