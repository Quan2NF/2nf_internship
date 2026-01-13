<?php

namespace App\Listeners\Issue;

use App\Events\Issue\IssueCreated;
use App\Jobs\SendIssueNotificationJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class NotifyIssueCreatedListener implements ShouldQueue
{
    use InteractsWithQueue;

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

        // Log issue creation
        Log::info("Issue Created", [
            'issue_id' => $issue->id,
            'title' => $issue->title,
            'created_by' => $creator->name,
            'created_at' => now()->toIso8601String(),
        ]);

        // Dispatch job to send notifications
        SendIssueNotificationJob::dispatch($issue, $creator);
    }
}

