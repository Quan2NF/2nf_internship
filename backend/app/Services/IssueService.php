<?php

namespace App\Services;

use App\Events\Issue\IssueAssigned;
use App\Events\Issue\IssueCreated;
use App\Events\Issue\IssueDeleted;
use App\Events\Issue\IssueStatusChanged;
use App\Events\Issue\IssueUpdated;
use App\Models\Issue;
use App\Models\User;
use App\Repositories\Contracts\IssueRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class IssueService
{
    public function __construct(
        protected IssueRepositoryInterface $issueRepository
    ) {}

    public function getAll(array $filters = []): Collection
    {
        $query = $this->issueRepository->findActive();

        // Apply filters
        if (isset($filters['project_id'])) {
            $query = $query->where('project_id', $filters['project_id']);
        }

        if (isset($filters['status'])) {
            $query = $query->where('status', $filters['status']);
        }

        if (isset($filters['priority'])) {
            $query = $query->where('priority', $filters['priority']);
        }

        if (isset($filters['type'])) {
            $query = $query->where('type', $filters['type']);
        }

        if (isset($filters['assigned_to'])) {
            $query = $query->where('assigned_to', $filters['assigned_to']);
        }

        return $query->get();
    }

    public function findById(int $id): ?Issue
    {
        return $this->issueRepository->find($id);
    }

    public function create(array $data, User $user): Issue
    {
        $data['reported_by'] = $user->id;
        $data['created_by'] = $user->id;
        $data['updated_by'] = $user->id;

        $issue = $this->issueRepository->create($data);

        // Dispatch event for issue created
        event(new IssueCreated($issue, $user));

        return $issue;
    }

    public function update(int $id, array $data, User $user): ?Issue
    {
        $issue = $this->findById($id);

        if (!$issue) {
            return null;
        }

        $data['updated_by'] = $user->id;
        $this->issueRepository->update($id, $data);

        $updatedIssue = $this->findById($id);

        // Dispatch event for issue updated
        event(new IssueUpdated($updatedIssue));

        return $updatedIssue;
    }

    public function delete(int $id, User $user): bool
    {
        $issue = $this->findById($id);

        if (!$issue) {
            return false;
        }

        $result = $this->issueRepository->delete($id);

        if ($result) {
            // Dispatch event for issue deleted
            event(new IssueDeleted($issue));
        }

        return $result;
    }

    public function getByProject(int $projectId): Collection
    {
        return $this->issueRepository->findByProject($projectId);
    }

    public function getByAssignee(int $userId): Collection
    {
        return $this->issueRepository->findByAssignee($userId);
    }

    public function getByReporter(int $userId): Collection
    {
        return $this->issueRepository->findByReporter($userId);
    }

    public function getOpenIssues(): Collection
    {
        return $this->issueRepository->findOpen();
    }

    public function getClosedIssues(): Collection
    {
        return $this->issueRepository->findClosed();
    }

    public function getOverdueIssues(): Collection
    {
        return $this->issueRepository->findOverdue();
    }

    public function assignIssue(int $issueId, int $userId, User $currentUser): ?Issue
    {
        $issue = $this->findById($issueId);

        if (!$issue) {
            return null;
        }

        $data = [
            'assigned_to' => $userId,
            'updated_by' => $currentUser->id,
        ];

        if ($issue->status === Issue::STATUS_OPEN) {
            $data['status'] = Issue::STATUS_IN_PROGRESS;
        }

        $this->issueRepository->update($issueId, $data);

        $updatedIssue = $this->findById($issueId);

        // Dispatch event for issue assigned
        event(new IssueAssigned($updatedIssue, $userId));

        return $updatedIssue;
    }

    public function changeStatus(int $issueId, int $status, User $user): ?Issue
    {
        $issue = $this->findById($issueId);

        if (!$issue) {
            return null;
        }

        $oldStatus = $issue->status;

        $data = [
            'status' => $status,
            'updated_by' => $user->id,
        ];

        // If closing the issue, set resolved_at timestamp
        if (in_array($status, [Issue::STATUS_RESOLVED, Issue::STATUS_CLOSED])) {
            $data['resolution'] = $issue->resolution ?? 'Issue resolved';
        }

        $this->issueRepository->update($issueId, $data);

        $updatedIssue = $this->findById($issueId);

        // Dispatch event for status changed
        event(new IssueStatusChanged($updatedIssue, $status, $oldStatus));

        return $updatedIssue;
    }

    public function restore(int $id): bool
    {
        $issue = Issue::onlyTrashed()->findOrFail($id);
        return $issue->restore();
    }

    public function getTrashed(): Collection
    {
        return Issue::onlyTrashed()->get();
    }
}
