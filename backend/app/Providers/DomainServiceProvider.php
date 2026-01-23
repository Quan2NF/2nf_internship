<?php

namespace App\Providers;

use App\Service\TaskService;
use App\Service\UserService;
use App\Service\ProjectService;
use App\Service\PositionService;
use App\Service\AuthenticationService;
use Illuminate\Support\ServiceProvider;
use App\Contracts\Service\TaskServiceInterface;
use App\Contracts\Service\UserServiceInterface;
use App\Contracts\Service\ProjectServiceInterface;
use App\Contracts\Service\PositionServiceInterface;
use App\Contracts\Service\AuthenticationServiceInterface;

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

        $this->app->bind(
            PositionServiceInterface::class,
            PositionService::class
        );

        $this->app->bind(
            ProjectServiceInterface::class,
            ProjectService::class
        );

        $this->app->bind(
            TaskServiceInterface::class,
            TaskService::class
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
