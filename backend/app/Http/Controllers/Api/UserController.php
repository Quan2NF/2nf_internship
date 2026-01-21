<?php

namespace App\Http\Controllers\Api;

use App\Data\User\UserData;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\ApiResponse;
use App\Http\Requests\User\AssignRoleRequest;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Models\User;
use App\Services\Interfaces\IUserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly IUserService $userService
    ) {}

    private function ensureAdmin(int $authUserId): void
    {
        $isAdmin = DB::table('project_members as pm')
            ->join('project_member_roles as pmr', 'pmr.project_member_id', '=', 'pm.id')
            ->join('roles as r', 'r.id', '=', 'pmr.role_id')
            ->where('pm.user_id', $authUserId)
            ->where('r.code', 'ADMIN')
            ->exists();

        if (!$isAdmin) {
            abort(403, 'Forbidden');
        }
    }

    /**
     * GET /users?keyword=abc&per_page=15
     */
    public function index(Request $request)
    {
        $this->ensureAdmin((int) $request->user()->id);

        $perPage = (int) $request->query('per_page', 15);
        $keyword = (string) $request->query('keyword', '');

        // Nếu bạn chưa có filter trong service thì tạm paginate thường
        $p = $this->userService->paginate($perPage);

        $items = collect($p->items())->map(fn ($u) => UserData::fromModel($u));

        return $this->success(
            message: 'GET_USERS_SUCCESS',
            data: [
                'items' => $items,
                'meta' => [
                    'current_page' => $p->currentPage(),
                    'per_page' => $p->perPage(),
                    'total' => $p->total(),
                    'last_page' => $p->lastPage(),
                ],
            ]
        );
    }

    /**
     * POST /users
     */
    public function store(UserCreateRequest $request)
    {
        $this->ensureAdmin((int) $request->user()->id);

        $user = $this->userService->create($request->validated());

        return $this->success(
            message: 'CREATE_USER_SUCCESS',
            data: UserData::fromModel($user)
        );
    }

    /**
     * PATCH /users/{id}
     */
    public function update(int $id, UserUpdateRequest $request)
    {
        $this->ensureAdmin((int) $request->user()->id);

        $ok = $this->userService->update($id, $request->validated());
        if (!$ok) {
            abort(404, 'User not found');
        }

        $user = $this->userService->find($id);

        return $this->success(
            message: 'UPDATE_USER_SUCCESS',
            data: $user ? UserData::fromModel($user) : null
        );
    }

    /**
     * DELETE /users/{id}
     */
    public function destroy(int $id, Request $request)
    {
        $this->ensureAdmin((int) $request->user()->id);

        $ok = $this->userService->softDelete($id);
        if (!$ok) {
            abort(404, 'User not found');
        }

        return $this->success(
            message: 'DELETE_USER_SUCCESS',
            data: ['deleted' => true]
        );
    }

    /**
     * POST /users/assign-role
     * Assign roles to a user in a project
     */
    public function assignRole(AssignRoleRequest $request)
    {
        $this->ensureAdmin((int) $request->user()->id);

        $data = $request->validated();

        // Bạn sẽ implement logic này trong service/repo 
        $this->userService->assignRolesInProject(
            projectId: (int) $data['project_id'],
            userId: (int) $data['user_id'],
            roleCodes: $data['role_codes']
        );

        return $this->success(message: 'ASSIGN_ROLE_SUCCESS');
    }

    /**
     * GET /users/{id}/roles?project_id=1
     */
    public function roles(int $id, Request $request)
    {
        $this->ensureAdmin((int) $request->user()->id);

        $projectId = (int) $request->query('project_id', 0);
        if ($projectId <= 0) {
            abort(422, 'project_id is required');
        }

        $roles = $this->userService->getRolesInProject($projectId, $id);

        return $this->success(
            message: 'GET_USER_ROLES_SUCCESS',
            data: $roles
        );
    }
}
