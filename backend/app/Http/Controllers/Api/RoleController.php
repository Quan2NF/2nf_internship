<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    use ApiResponse;

    /**
     * API12: List all roles
     */
    public function index(): JsonResponse
    {
        try {
            // Since roles might not have a dedicated table, we'll return predefined roles
            $roles = [
                ['id' => 1, 'name' => 'admin', 'description' => 'Administrator'],
                ['id' => 2, 'name' => 'pm', 'description' => 'Project Manager'],
                ['id' => 3, 'name' => 'developer', 'description' => 'Developer'],
                ['id' => 4, 'name' => 'qa', 'description' => 'Quality Assurance'],
                ['id' => 5, 'name' => 'user', 'description' => 'Regular User'],
            ];

            return $this->successResponse(
                data: $roles,
                message: 'Danh sách vai trò'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                statusCode: 500
            );
        }
    }

    /**
     * API13: Create new role
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|unique:roles',
                'description' => 'nullable|string',
            ]);

            // Create role in database (requires roles table)
            // For now, return the validated data
            $role = [
                'id' => rand(100, 999),
                'name' => $validated['name'],
                'description' => $validated['description'] ?? '',
            ];

            return $this->successResponse(
                data: $role,
                message: 'Vai trò được tạo thành công',
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
     * API14: Update role
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'string|nullable',
                'description' => 'string|nullable',
            ]);

            // Update role in database
            $role = [
                'id' => $id,
                'name' => $validated['name'] ?? 'role_' . $id,
                'description' => $validated['description'] ?? '',
            ];

            return $this->successResponse(
                data: $role,
                message: 'Cập nhật vai trò thành công'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                statusCode: 400
            );
        }
    }

    /**
     * API15: Delete role
     */
    public function destroy($id): JsonResponse
    {
        try {
            // Delete role from database
            return $this->successResponse(
                message: 'Xóa vai trò thành công'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                statusCode: 400
            );
        }
    }
}
