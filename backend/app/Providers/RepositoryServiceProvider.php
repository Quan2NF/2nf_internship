<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repository\Implementations\UserRepository;
use App\Repository\Implementations\ProjectRepository;
use App\Repository\Implementations\IssueRepository;
use App\Repository\Implementations\DocumentRepository;
use App\Repository\Implementations\SprintReporitory;
use App\Repository\Implementations\MilestoneRepository;

use App\Repository\Interfaces\UserRepositoryInterface;
use App\Repository\Interfaces\ProjectRepositoryInterface;
use App\Repository\Interfaces\IssueRepositoryInterface;
use App\Repository\Interfaces\DocumentRepositoryInterface;
use App\Repository\Interfaces\SprintRepositoryInterface;
use App\Repository\Interfaces\MilestoneRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->scoped(
            UserRepositoryInterface::class,
            UserRepository::class,
        );

        $this->app->scoped(
            ProjectRepositoryInterface::class,
            ProjectRepository::class,
        );

        $this->app->scoped(
            IssueRepositoryInterface::class,
            IssueRepository::class,
        );

        $this->app->scoped(
            DocumentRepositoryInterface::class,
            DocumentRepository::class,
        );

        $this->app->scoped(
            SprintRepositoryInterface::class,
            SprintReporitory::class,
        );

        $this->app->scoped(
            MilestoneRepositoryInterface::class,
            MilestoneRepository::class,
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
