<?php

namespace App\Http\Controllers\Api;

use App\Data\User\UserData;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\ApiResponse;
use App\Http\Requests\User\AssignRoleRequest;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Services\Interfaces\IUserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly IUserService $userService
    ) {}

    /**
     * Only system ADMIN can manage users.
     */
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
     * AP05/AP06: List / Filter Users (Admin only)
     * GET /users?keyword=&is_active=&per_page=
     */
    public function index(Request $request)
    {
        $this->ensureSystemAdmin((int) $request->user()->id);

        $filters = [
            'keyword' => $request->query('keyword'),
            'is_active' => $request->query('is_active'),
        ];
        $perPage = (int) $request->query('per_page', 15);

        $p = $this->userService->paginateFiltered($filters, $perPage);

        $items = array_map(
            fn ($u) => UserData::fromModel($u),
            $p->items()
        );

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
     * AP07: Create User (Admin only)
     * POST /users
     */
    public function store(UserCreateRequest $request)
    {
        $this->ensureSystemAdmin((int) $request->user()->id);

        $user = $this->userService->create($request->validated());

        return $this->success(
            message: 'CREATE_USER_SUCCESS',
            data: UserData::fromModel($user)
        );
    }

    /**
     * AP08: Edit User (Admin only)
     * PATCH /users/{id}
     */
    public function update(int $id, UserUpdateRequest $request)
    {
        $this->ensureSystemAdmin((int) $request->user()->id);

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
     * AP09: Delete User (soft delete) (Admin only)
     * DELETE /users/{id}
     */
    public function destroy(int $id, Request $request)
    {
        $this->ensureSystemAdmin((int) $request->user()->id);

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
     * AP10: Assign Role (System roles) (Admin only)
     * POST /users/assign-role
     */
    public function assignRole(AssignRoleRequest $request)
    {
        $this->ensureSystemAdmin((int) $request->user()->id);

        $data = $request->validated();
        $mode = $data['mode'] ?? 'sync';

        $this->userService->assignSystemRoles(
            userId: (int) $data['user_id'],
            roleCodes: (array) $data['role_codes'],
            mode: (string) $mode
        );

        return $this->success(message: 'ASSIGN_ROLE_SUCCESS');
    }

    /**
     * AP11: List User Roles (System roles) (Admin only)
     * GET /users/{id}/roles
     */
    public function roles(int $id, Request $request)
    {
        $this->ensureSystemAdmin((int) $request->user()->id);

        $roles = $this->userService->getSystemRoles($id);

        return $this->success(
            message: 'GET_USER_ROLES_SUCCESS',
            data: ['roles' => $roles]
        );
    }
}
