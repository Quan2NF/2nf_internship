<?php

namespace App\Listeners\Issue;

use App\Events\Issue\IssueAssigned;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class NotifyIssueAssignedListener implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(IssueAssigned $event): void
    {
        Log::info('Issue assigned event triggered', [
            'issue_id' => $event->issue->id,
            'issue_title' => $event->issue->title,
            'assigned_to' => $event->assigneeId,
            'project_id' => $event->issue->project_id,
        ]);

        // TODO: Dispatch SendIssueNotificationJob when email template ready
        // dispatch(new SendIssueNotificationJob($event->issue, 'assigned', $event->assigneeId));
    }
}
