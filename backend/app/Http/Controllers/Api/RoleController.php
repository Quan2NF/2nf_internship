<?php

namespace App\Http\Controllers\Api;

use App\Data\Role\RoleData;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\ApiResponse;
use App\Http\Requests\Role\RoleCreateRequest;
use App\Http\Requests\Role\RoleUpdateRequest;
use App\Services\Interfaces\IRoleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly IRoleService $roleService
    ) {}

    private function ensureSystemAdmin(int $authUserId): void
    {
        $isAdmin = DB::table('user_system_roles as usr')
            ->join('roles as r', 'r.id', '=', 'usr.role_id')
            ->where('usr.user_id', $authUserId)
            ->where('r.code', 'ADMIN')
            ->exists();

        if (!$isAdmin) {
            abort(403, 'Forbidden');
        }
    }

    /**
     * AP12: List roles
     */
    public function index(Request $request)
    {
        $this->ensureSystemAdmin((int) $request->user()->id);

        $roles = $this->roleService->list()
            ->map(fn ($r) => RoleData::fromModel($r))
            ->values()
            ->all();

        return $this->success(message: 'GET_ROLES_SUCCESS', data: ['roles' => $roles]);
    }

    /**
     * AP13: Create new role
     */
    public function store(RoleCreateRequest $request)
    {
        $this->ensureSystemAdmin((int) $request->user()->id);

        $role = $this->roleService->create($request->validated());

        return $this->success(message: 'CREATE_ROLE_SUCCESS', data: RoleData::fromModel($role));
    }

    /**
     * AP14: Edit role
     */
    public function update(int $id, RoleUpdateRequest $request)
    {
        $this->ensureSystemAdmin((int) $request->user()->id);

        $ok = $this->roleService->update($id, $request->validated());
        if (!$ok) {
            abort(404, 'Role not found');
        }

        return $this->success(message: 'UPDATE_ROLE_SUCCESS');
    }

    /**
     * AP15: Delete role
     */
    public function destroy(int $id, Request $request)
    {
        $this->ensureSystemAdmin((int) $request->user()->id);

        $ok = $this->roleService->delete($id);
        if (!$ok) {
            abort(400, 'Cannot delete role');
        }

        return $this->success(message: 'DELETE_ROLE_SUCCESS', data: ['deleted' => true]);
    }
}
