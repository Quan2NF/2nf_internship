<?php

namespace App\Jobs;

use App\Models\Issue;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendIssueNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private Issue $issue,
        private User $creator
    ) {
        $this->onQueue('default');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Send email to assignee (if assigned)
            if ($this->issue->assigned_to) {
                $assignee = User::find($this->issue->assigned_to);
                if ($assignee) {
                    $this->sendAssigneeNotification($assignee);
                }
            }

            // Send email to project creator
            $projectCreator = User::find($this->issue->project->created_by);
            if ($projectCreator && $projectCreator->id !== $this->creator->id) {
                $this->sendProjectCreatorNotification($projectCreator);
            }

            Log::info('Issue notifications sent', [
                'issue_id' => $this->issue->id,
                'assignee_notified' => $this->issue->assigned_to !== null,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send issue notifications', [
                'issue_id' => $this->issue->id,
                'error' => $e->getMessage(),
            ]);

            // Retry the job
            throw $e;
        }
    }

    /**
     * Send notification to assignee
     */
    private function sendAssigneeNotification(User $assignee): void
    {
        // TODO: Implement email notification using Laravel Mail
        // Mailable::to($assignee->email)
        //     ->send(new IssueAssignedNotification($this->issue, $this->creator));

        Log::info('Issue assigned notification', [
            'issue_id' => $this->issue->id,
            'assignee_id' => $assignee->id,
            'assignee_email' => $assignee->email,
        ]);
    }

    /**
     * Send notification to project creator
     */
    private function sendProjectCreatorNotification(User $projectCreator): void
    {
        // TODO: Implement email notification using Laravel Mail
        // Mailable::to($projectCreator->email)
        //     ->send(new IssueCreatedNotification($this->issue, $this->creator));

        Log::info('Issue creation notification', [
            'issue_id' => $this->issue->id,
            'project_creator_id' => $projectCreator->id,
            'project_creator_email' => $projectCreator->email,
        ]);
    }
}
