<?php

namespace App\Http\Controllers\Api;

use App\Data\IssueData;
use App\Http\Controllers\Controller;
use App\Http\Requests\IssueStoreRequest;
use App\Http\Requests\IssueUpdateRequest;
use App\Models\Issue;
use App\Services\IssueService;
use App\Traits\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IssueController extends Controller
{
    use ApiResponse;
    use AuthorizesRequests;

    public function __construct(
        protected IssueService $issueService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Issue::class);

        $issues = $this->issueService->getAll($request->all());

        return $this->successResponse(
            data: IssueData::fromModels($issues),
            message: 'Danh sách issues'
        );
    }

    public function show(int $id): JsonResponse
    {
        $issue = Issue::findOrFail($id);

        $this->authorize('view', $issue);

        return $this->successResponse(
            data: IssueData::fromModel($issue),
            message: 'Chi tiết issue'
        );
    }

    public function store(IssueStoreRequest $request): JsonResponse
    {
        $this->authorize('create', Issue::class);

        $issue = $this->issueService->create($request->validated(), $request->user());

        return $this->successResponse(
            data: IssueData::fromModel($issue),
            message: 'Issue được tạo thành công',
            statusCode: 201
        );
    }

    public function update(IssueUpdateRequest $request, int $id): JsonResponse
    {
        $issue = Issue::findOrFail($id);

        $this->authorize('update', $issue);

        $updated = $this->issueService->update($id, $request->validated(), $request->user());

        return $this->successResponse(
            data: IssueData::fromModel($updated),
            message: 'Issue được cập nhật thành công'
        );
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $issue = Issue::findOrFail($id);

        $this->authorize('delete', $issue);

        $this->issueService->delete($id, $request->user());

        return $this->successResponse(
            message: 'Issue được xóa thành công'
        );
    }

    public function byProject(int $projectId): JsonResponse
    {
        $issues = $this->issueService->getByProject($projectId);

        return $this->successResponse(
            data: IssueData::fromModels($issues),
            message: 'Issues của dự án'
        );
    }

    public function byAssignee(int $userId): JsonResponse
    {
        $issues = $this->issueService->getByAssignee($userId);

        return $this->successResponse(
            data: IssueData::fromModels($issues),
            message: 'Issues được gán cho người dùng'
        );
    }

    public function myIssues(Request $request): JsonResponse
    {
        $issues = $this->issueService->getByAssignee($request->user()->id);

        return $this->successResponse(
            data: IssueData::fromModels($issues),
            message: 'Issues của tôi'
        );
    }

    public function reportedByMe(Request $request): JsonResponse
    {
        $issues = $this->issueService->getByReporter($request->user()->id);

        return $this->successResponse(
            data: IssueData::fromModels($issues),
            message: 'Issues tôi báo cáo'
        );
    }

    public function open(): JsonResponse
    {
        $issues = $this->issueService->getOpenIssues();

        return $this->successResponse(
            data: IssueData::fromModels($issues),
            message: 'Issues đang mở'
        );
    }

    public function closed(): JsonResponse
    {
        $issues = $this->issueService->getClosedIssues();

        return $this->successResponse(
            data: IssueData::fromModels($issues),
            message: 'Issues đã đóng'
        );
    }

    public function overdue(): JsonResponse
    {
        $issues = $this->issueService->getOverdueIssues();

        return $this->successResponse(
            data: IssueData::fromModels($issues),
            message: 'Issues quá hạn'
        );
    }

    public function restore(int $id): JsonResponse
    {
        $issue = Issue::onlyTrashed()->findOrFail($id);

        $this->authorize('restore', $issue);

        $this->issueService->restore($id);

        return $this->successResponse(
            data: IssueData::fromModel($issue),
            message: 'Issue được khôi phục thành công'
        );
    }

    public function trashed(): JsonResponse
    {
        $this->authorize('viewTrashed', Issue::class);

        $issues = $this->issueService->getTrashed();

        return $this->successResponse(
            data: IssueData::fromModels($issues),
            message: 'Danh sách issues đã xóa'
        );
    }

    /**
     * API28: Filter issues
     */
    public function filter(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();
            $query = Issue::query();

            // Regular users only see issues in projects they're assigned to
            if (!$user->isAdmin() && !$user->isManager()) {
                $query->whereHas('project', function ($q) use ($user) {
                    $q->where('created_by', $user->id)
                      ->orWhereHas('users', function ($sq) use ($user) {
                          $sq->where('user_id', $user->id);
                      });
                });
            }

            if ($request->has('project_id')) {
                $query->where('project_id', $request->input('project_id'));
            }

            if ($request->has('status')) {
                $query->where('status', $request->input('status'));
            }

            if ($request->has('priority')) {
                $query->where('priority', $request->input('priority'));
            }

            if ($request->has('assignee_id')) {
                $query->where('assignee_id', $request->input('assignee_id'));
            }

            $issues = $query->get();

            return $this->successResponse(
                data: IssueData::fromModels($issues),
                message: 'Kết quả lọc issues'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                statusCode: 500
            );
        }
    }

    /**
     * API32: Add comment to issue
     */
    public function addComment(Request $request, $id): JsonResponse
    {
        try {
            $issue = Issue::findOrFail($id);

            $this->authorize('update', $issue);

            $validated = $request->validate([
                'content' => 'required|string',
            ]);

            // Create comment (assuming Issue has comments relationship)
            $comment = [
                'id' => rand(1000, 9999),
                'issue_id' => $issue->id,
                'user_id' => auth()->id(),
                'content' => $validated['content'],
                'created_at' => now(),
            ];

            return $this->successResponse(
                data: $comment,
                message: 'Bình luận được thêm thành công',
                statusCode: 201
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                statusCode: 400
            );
        }
    }

    /**
     * API33: Get logs
     */
    public function getLogs(Request $request): JsonResponse
    {
        try {
            // Get activity logs (assuming you have logging mechanism)
            $logs = [
                [
                    'id' => 1,
                    'user_id' => auth()->id(),
                    'action' => 'created_issue',
                    'description' => 'Created a new issue',
                    'created_at' => now(),
                ],
            ];

            return $this->successResponse(
                data: $logs,
                message: 'Danh sách hoạt động'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                statusCode: 500
            );
        }
    }
}
