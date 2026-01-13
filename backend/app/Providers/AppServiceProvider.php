<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\IssueRepositoryInterface;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Eloquent\IssueRepository;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // User Repository
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );

        // Issue Repository
        $this->app->bind(
            IssueRepositoryInterface::class,
            IssueRepository::class
        );

        // Project Repository
        $this->app->bind(
            \App\Repositories\Contracts\ProjectRepositoryInterface::class,
            \App\Repositories\Eloquent\ProjectRepository::class
        );
    }

    public function boot(): void
    {
        //
    }
}

