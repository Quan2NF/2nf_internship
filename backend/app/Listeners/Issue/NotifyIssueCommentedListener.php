<?php

namespace App\Listeners\Issue;

use App\Events\Issue\IssueCommented;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class NotifyIssueCommentedListener implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(IssueCommented $event): void
    {
        Log::info('Issue commented event triggered', [
            'issue_id' => $event->issue->id,
            'issue_title' => $event->issue->title,
            'project_id' => $event->issue->project_id,
        ]);

        // TODO: Dispatch SendIssueNotificationJob when email template ready
        // dispatch(new SendIssueNotificationJob($event->issue, 'commented'));
    }
}
