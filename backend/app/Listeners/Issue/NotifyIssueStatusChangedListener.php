<?php

namespace App\Listeners\Issue;

use App\Events\Issue\IssueStatusChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class NotifyIssueStatusChangedListener implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(IssueStatusChanged $event): void
    {
        Log::info('Issue status changed event triggered', [
            'issue_id' => $event->issue->id,
            'issue_title' => $event->issue->title,
            'old_status' => $event->oldStatus,
            'new_status' => $event->newStatus,
            'project_id' => $event->issue->project_id,
        ]);

        // TODO: Dispatch SendIssueNotificationJob when email template ready
        // dispatch(new SendIssueNotificationJob($event->issue, 'status_changed'));
    }
}
