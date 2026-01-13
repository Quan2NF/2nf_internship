<?php

namespace App\Listeners\Issue;

use App\Events\Issue\IssueDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class NotifyIssueDeletedListener implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(IssueDeleted $event): void
    {
        Log::info('Issue deleted event triggered', [
            'issue_id' => $event->issue->id,
            'issue_title' => $event->issue->title,
            'project_id' => $event->issue->project_id,
        ]);

        // TODO: Dispatch SendIssueNotificationJob when email template ready
        // dispatch(new SendIssueNotificationJob($event->issue, 'deleted'));
    }
}
