<?php

namespace App\Http\Controllers;

use App\Data\CreateRoleData;
use App\Data\ListRolesData;
use App\Data\UpdateRoleData;
use App\Exceptions\BusinessException;
use App\Http\Requests\CreateRoleRequest;
use App\Http\Requests\ListRolesRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Role;
use App\Services\RoleServiceInterface;

class RoleController extends Controller
{
    public function __construct(
        private readonly RoleServiceInterface $roles
    ) {}

    // API12 - List roles
    public function index(ListRolesRequest $request)
    {
        $filter = ListRolesData::fromRequest($request);

        try {
            $result = $this->roles->listRoles($filter);

            return response()->json([
                'data' => $result,
            ], 200);
        } catch (BusinessException $e) {
            return response()->json([
                'statusCode' => $e->getStatusCode(),
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }
    }

    // API13 - Create role
    public function store(CreateRoleRequest $request)
    {
        $data = CreateRoleData::fromRequest($request);

        try {
            $result = $this->roles->createRole($data);

            return response()->json([
                'data' => $result,
            ], 201);
        } catch (BusinessException $e) {
            return response()->json([
                'statusCode' => $e->getStatusCode(),
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }
    }

    // API14 - Edit role
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $data = UpdateRoleData::fromRequest($request);

        try {
            $result = $this->roles->updateRole($role->id, $data);

            return response()->json([
                'data' => $result,
            ], 200);
        } catch (BusinessException $e) {
            return response()->json([
                'statusCode' => $e->getStatusCode(),
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }
    }

    // API15 - Delete role
    public function destroy(Role $role)
    {
        try {
            $this->roles->deleteRole($role->id);

            return response()->json([
                'message' => 'Role deleted successfully',
            ], 200);
        } catch (BusinessException $e) {
            return response()->json([
                'statusCode' => $e->getStatusCode(),
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }
    }
}
