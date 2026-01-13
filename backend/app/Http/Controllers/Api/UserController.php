<?php

namespace App\Http\Controllers\Api;

use App\Data\Auth\UserData;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    use ApiResponse;

    /**
     * API05: Get all users
     */
    public function index(): JsonResponse
    {
        try {
            $users = User::all();

            return $this->successResponse(
                data: UserData::fromModels($users),
                message: 'Danh sách người dùng'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                statusCode: 500
            );
        }
    }

    /**
     * API06: Filter users
     */
    public function filter(Request $request): JsonResponse
    {
        try {
            $query = User::query();

            if ($request->has('name')) {
                $query->where('name', 'like', '%' . $request->input('name') . '%');
            }

            if ($request->has('email')) {
                $query->where('email', 'like', '%' . $request->input('email') . '%');
            }

            if ($request->has('is_active')) {
                $query->where('is_active', $request->input('is_active'));
            }

            if ($request->has('role')) {
                $query->where('role', $request->input('role'));
            }

            $users = $query->get();

            return $this->successResponse(
                data: UserData::fromModels($users),
                message: 'Kết quả lọc người dùng'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                statusCode: 500
            );
        }
    }

    /**
     * API07: Create new user (Admin only)
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = User::create($validated);

        return $this->successResponse(
            data: UserData::fromModel($user),
            message: 'Người dùng được tạo thành công',
            statusCode: 201
        );
    }

    /**
     * API08: Update user
     */
    public function update(Request $request, $id): JsonResponse
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'string|nullable',
            'email' => 'email|nullable|unique:users,email,' . $id,
            'phone_number' => 'nullable|string',
            'birthday' => 'nullable|date',
            'gender' => 'nullable|in:1,2,3',
            'is_active' => 'nullable|boolean',
        ]);

        $user->update($validated);

        return $this->successResponse(
            data: UserData::fromModel($user),
            message: 'Cập nhật người dùng thành công'
        );
    }

    /**
     * API09: Delete user
     */
    public function destroy($id): JsonResponse
    {
        $user = User::findOrFail($id);
        $user->delete();

        return $this->successResponse(
            message: 'Xóa người dùng thành công'
        );
    }

    /**
     * API10: Assign role to user
     */
    public function assignRole(Request $request, $id): JsonResponse
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'role' => 'required|string',
        ]);

        $user->update(['role' => $validated['role']]);

        return $this->successResponse(
            data: UserData::fromModel($user),
            message: 'Gán vai trò thành công'
        );
    }

    /**
     * API11: Get user roles
     */
    public function getUserRoles($id): JsonResponse
    {
        $user = User::findOrFail($id);

        return $this->successResponse(
            data: [
                'user_id' => $user->id,
                'role' => $user->role,
            ],
            message: 'Vai trò của người dùng'
        );
    }
}
