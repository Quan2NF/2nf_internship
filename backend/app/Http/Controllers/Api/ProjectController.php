<?php
namespace App\Http\Controllers\Api;

use App\Data\ProjectData;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectStoreRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Models\Project;
use App\Services\ProjectService;
use App\Traits\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    use ApiResponse;
    use AuthorizesRequests;

    public function __construct(
        protected ProjectService $projectService
    ) {}

    public function index(): JsonResponse
    {
        $this->authorize('viewAny', Project::class);

        $user = auth()->user();
        $projects = $user->isAdmin() || $user->isManager()
            ? $this->projectService->list()
            : $user->projects()->get();

        return $this->successResponse(
            data: ProjectData::fromModels($projects),
            message: 'Danh sách dự án'
        );
    }

    public function store(ProjectStoreRequest $request): JsonResponse
    {
        $this->authorize('create', Project::class);

        $project = $this->projectService->create(
            $request->validated(),
            auth()->id()
        );

        return $this->successResponse(
            data: ProjectData::fromModel($project),
            message: 'Dự án được tạo thành công',
            statusCode: 201
        );
    }

    public function show(int $id): JsonResponse
    {
        $project = Project::findOrFail($id);

        $this->authorize('view', $project);

        return $this->successResponse(
            data: ProjectData::fromModel($project),
            message: 'Chi tiết dự án'
        );
    }

    public function update(ProjectUpdateRequest $request, int $id): JsonResponse
    {
        $project = Project::findOrFail($id);

        $this->authorize('update', $project);

        $updated = $this->projectService->update($id, $request->validated(), auth()->id());

        return $this->successResponse(
            data: ProjectData::fromModel($updated),
            message: 'Dự án được cập nhật thành công'
        );
    }

    public function destroy(int $id): JsonResponse
    {
        $project = Project::findOrFail($id);

        $this->authorize('delete', $project);

        $this->projectService->delete($id);

        return $this->successResponse(
            message: 'Dự án được xóa thành công'
        );
    }

    public function restore(int $id): JsonResponse
    {
        $project = Project::onlyTrashed()->findOrFail($id);

        $this->authorize('restore', $project);

        $this->projectService->restore($id);

        return $this->successResponse(
            data: ProjectData::fromModel($project),
            message: 'Dự án được khôi phục thành công'
        );
    }

    public function trashed(): JsonResponse
    {
        $this->authorize('viewTrashed', Project::class);

        $projects = $this->projectService->trashed();

        return $this->successResponse(
            data: ProjectData::fromModels($projects),
            message: 'Danh sách dự án đã xóa'
        );
    }

    /**
     * API17: Filter projects
     */
    public function filter(\Illuminate\Http\Request $request): JsonResponse
    {
        try {
            $user = auth()->user();
            $query = Project::query();

            // Regular users only see their assigned projects
            if (!$user->isAdmin() && !$user->isManager()) {
                $query->whereHas('users', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })->orWhere('created_by', $user->id);
            }

            if ($request->has('name')) {
                $query->where('name', 'like', '%' . $request->input('name') . '%');
            }

            if ($request->has('status')) {
                $query->where('status', $request->input('status'));
            }

            if ($request->has('pm_id')) {
                $query->where('pm_id', $request->input('pm_id'));
            }

            $projects = $query->get();

            return $this->successResponse(
                data: ProjectData::fromModels($projects),
                message: 'Kết quả lọc dự án'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                statusCode: 500
            );
        }
    }

    /**
     * API21: Assign PM to project
     */
    public function assignPM(\Illuminate\Http\Request $request, $id): JsonResponse
    {
        try {
            $project = Project::findOrFail($id);

            $this->authorize('update', $project);

            $validated = $request->validate([
                'pm_id' => 'required|exists:users,id',
            ]);

            $project->update(['pm_id' => $validated['pm_id']]);

            return $this->successResponse(
                data: ProjectData::fromModel($project),
                message: 'Gán người quản lý dự án thành công'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                statusCode: 400
            );
        }
    }

    /**
     * API22: Assign members to project
     */
    public function assignMembers(\Illuminate\Http\Request $request, $id): JsonResponse
    {
        try {
            $project = Project::findOrFail($id);

            $this->authorize('update', $project);

            $validated = $request->validate([
                'member_ids' => 'required|array',
                'member_ids.*' => 'exists:users,id',
            ]);

            // Assuming project has members relationship
            $project->users()->sync($validated['member_ids']);

            return $this->successResponse(
                data: ProjectData::fromModel($project),
                message: 'Gán thành viên dự án thành công'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                statusCode: 400
            );
        }
    }
}

