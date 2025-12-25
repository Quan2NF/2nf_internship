<?php

namespace App\Listeners\Issue;

use App\Events\Issue\IssueAssigned;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyIssueAssignedListener
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
    public function handle(IssueAssigned $event): void
    {
        $issue = $event->issue;
        $assignee = $event->assignee;
        $assigner = $event->assigner;

        // TODO: Implement notification logic
        // - Send email to assignee
        // - Send in-app notification to assignee
        // - Notify project members
        // - Log assignment activity

        \Log::info("Issue Assigned: {$issue->title} assigned to {$assignee->name} by {$assigner->name}");

        // Here you could dispatch a job to send notifications asynchronously
        // dispatch(new SendIssueAssignmentNotificationJob($issue, $assignee, $assigner));
    }
}
