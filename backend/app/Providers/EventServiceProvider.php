<?php

namespace App\Providers;

use App\Events\Issue\IssueCreated;
use App\Events\Issue\IssueUpdated;
use App\Events\Issue\IssueDeleted;
use App\Events\Issue\IssueAssigned;
use App\Events\Issue\IssueStatusChanged;
use App\Events\Issue\IssueCommented;
use App\Listeners\Issue\NotifyIssueCreatedListener;
use App\Listeners\Issue\NotifyIssueUpdatedListener;
use App\Listeners\Issue\NotifyIssueDeletedListener;
use App\Listeners\Issue\NotifyIssueAssignedListener;
use App\Listeners\Issue\NotifyIssueStatusChangedListener;
use App\Listeners\Issue\NotifyIssueCommentedListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        // Issue Events
        IssueCreated::class => [
            NotifyIssueCreatedListener::class,
        ],
        IssueUpdated::class => [
            NotifyIssueUpdatedListener::class,
        ],
        IssueDeleted::class => [
            NotifyIssueDeletedListener::class,
        ],
        IssueAssigned::class => [
            NotifyIssueAssignedListener::class,
        ],
        IssueStatusChanged::class => [
            NotifyIssueStatusChangedListener::class,
        ],
        IssueCommented::class => [
            NotifyIssueCommentedListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
