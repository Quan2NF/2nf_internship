<?php

namespace App\Providers;

use App\Service\AuthenticationService;
use Illuminate\Support\ServiceProvider;
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
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
