<?php

namespace App\Listeners\Issue;

use App\Events\Issue\IssueUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class NotifyIssueUpdatedListener implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(IssueUpdated $event): void
    {
        Log::info('Issue updated event triggered', [
            'issue_id' => $event->issue->id,
            'issue_title' => $event->issue->title,
            'project_id' => $event->issue->project_id,
        ]);

        // TODO: Dispatch SendIssueNotificationJob when email template ready
        // dispatch(new SendIssueNotificationJob($event->issue, 'updated'));
    }
}
