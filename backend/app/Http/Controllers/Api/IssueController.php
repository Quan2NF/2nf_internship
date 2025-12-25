<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\IssueService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IssueController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected IssueService $issueService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $issues = $this->issueService->getAll($request->all());

        return $this->successResponse($issues);
    }

    public function show(int $id): JsonResponse
    {
        $issue = $this->issueService->findById($id);

        if (!$issue) {
            return $this->notFoundResponse('Issue not found');
        }

        return $this->successResponse($issue);
    }

    public function store(Request $request): JsonResponse
    {
        $issue = $this->issueService->create($request->all(), $request->user());

        return $this->createdResponse($issue, 'Issue created successfully');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $issue = $this->issueService->update($id, $request->all(), $request->user());

        if (!$issue) {
            return $this->notFoundResponse('Issue not found');
        }

        return $this->successResponse($issue, 'Issue updated successfully');
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $result = $this->issueService->delete($id, $request->user());

        if (!$result) {
            return $this->notFoundResponse('Issue not found');
        }

        return $this->successResponse(null, 'Issue deleted successfully');
    }

    public function byProject(int $projectId): JsonResponse
    {
        $issues = $this->issueService->getByProject($projectId);

        return $this->successResponse($issues);
    }

    public function byAssignee(int $userId): JsonResponse
    {
        $issues = $this->issueService->getByAssignee($userId);

        return $this->successResponse($issues);
    }

    public function myIssues(Request $request): JsonResponse
    {
        $issues = $this->issueService->getByAssignee($request->user()->id);

        return $this->successResponse($issues);
    }

    public function reportedByMe(Request $request): JsonResponse
    {
        $issues = $this->issueService->getByReporter($request->user()->id);

        return $this->successResponse($issues);
    }

    public function open(): JsonResponse
    {
        $issues = $this->issueService->getOpenIssues();

        return $this->successResponse($issues);
    }

    public function closed(): JsonResponse
    {
        $issues = $this->issueService->getClosedIssues();

        return $this->successResponse($issues);
    }

    public function overdue(): JsonResponse
    {
        $issues = $this->issueService->getOverdueIssues();

        return $this->successResponse($issues);
    }
}
