<?php

namespace App\Listeners\Issue;

use App\Events\Issue\IssueCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyIssueCreatedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(IssueCreated $event): void
    {
        $issue = $event->issue;
        $creator = $event->creator;

        // TODO: Implement notification logic
        // - Send email to assignee (if assigned)
        // - Send email to project creator
        // - Create in-app notification
        // - Send Slack/Discord notification

        \Log::info("Issue Created: {$issue->title} by {$creator->name}");
    }
}
