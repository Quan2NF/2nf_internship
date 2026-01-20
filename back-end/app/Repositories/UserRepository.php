<?php

namespace App\Repositories;

use App\Data\CreateUserData;
use App\Data\ListUsersData;
use App\Data\UpdateUserData;
use App\Exceptions\BusinessException;
use App\Models\Position;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class UserRepository implements UserRepositoryInterface
{
    public function findActiveByEmail(string $email): ?User
    {
        return User::where('email', $email)
            ->where('is_active', 1)
            ->whereNull('deleted_at')
            ->first();
    }

    public function userIsAdmin(User $user): bool
    {
        return $user->isAdmin();
    }
    public function findByEmail(string $email): ?User
  {
    return User::where('email', $email)
        ->whereNull('deleted_at')
        ->first();               
}

    public function paginateUsers(ListUsersData $filter): LengthAwarePaginator
    {
        $query = User::query()
            ->with(['positions' => function ($q) {
                $q->select('positions.id', 'positions.code', 'positions.name');
            }]);

        if ($filter->search) {
            $search = trim($filter->search);
            $query->where(function ($q) use ($search) {
                $q->where('employee_code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        if ($filter->is_active) {
            $query->where('is_active', $filter->is_active);
        }

        if ($filter->position_id || $filter->position_code) {
            $query->whereHas('positions', function ($q) use ($filter) {
                if ($filter->position_id) {
                    $q->where('positions.id', $filter->position_id);
                }
                if ($filter->position_code) {
                    $q->where('positions.code', $filter->position_code);
                }
            });
        }

        if ($filter->join_date_from) {
            $query->whereDate('join_date', '>=', $filter->join_date_from);
        }
        if ($filter->join_date_to) {
            $query->whereDate('join_date', '<=', $filter->join_date_to);
        }

        // Sorting: name, email, join_date, created_at (prefix '-' for desc)
        $sort = $filter->sort ? trim($filter->sort) : null;
        $direction = 'asc';
        if ($sort && str_starts_with($sort, '-')) {
            $direction = 'desc';
            $sort = ltrim($sort, '-');
        }

        $allowedSorts = ['name', 'email', 'join_date', 'created_at'];
        if ($sort && in_array($sort, $allowedSorts, true)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('id', 'desc');
        }

        return $query->paginate($filter->per_page);
    }

    public function createUser(CreateUserData $data): array
    {
        return DB::transaction(function () use ($data) {
            $employeeCode = $data->employee_code ?: $this->generateNextEmployeeCode();
            if (User::where('employee_code', $employeeCode)->exists()) {
                throw new BusinessException('ERR_EMPLOYEE_CODE_EXISTS', 422);
            }

            $plainPassword = $data->password ?: 'password123';

            $user = User::create([
                'employee_code' => $employeeCode,
                'name' => $data->name,
                'email' => $data->email,
                'password' => $plainPassword,
                'phone_number' => $data->phone_number,
                'birthday' => $data->birthday,
                'gender' => $data->gender,
                'join_date' => $data->join_date,
                'resign_date' => $data->resign_date,
                'avatar' => $data->avatar,
                'is_active' => $data->is_active,
            ]);

            $positionsInput = $data->positions ?? [];
            if (count($positionsInput) === 0) {
                throw new BusinessException('ERR_POSITIONS_REQUIRED', 422);
            }

            $attach = [];
            foreach ($positionsInput as $pos) {
                $positionId = $pos['position_id'] ?? null;

                if (! $positionId && ! empty($pos['position_code'])) {
                    $positionId = Position::where('code', $pos['position_code'])->value('id');
                }

                if (! $positionId) {
                    throw new BusinessException('ERR_POSITION_NOT_FOUND', 422);
                }

                $attach[$positionId] = [
                    'start_date' => $pos['start_date'] ?? ($data->join_date ?: null),
                    'end_date' => $pos['end_date'] ?? null,
                ];
            }

            $user->positions()->sync($attach);

            $user->load(['positions' => function ($q) {
                $q->select('positions.id', 'positions.code', 'positions.name');
            }]);

            $response = [
                'id' => $user->id,
                'employee_code' => $user->employee_code,
                'name' => $user->name,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'join_date' => $user->join_date,
                'is_active' => $user->is_active,
                'positions' => $user->positions,
            ];

            if (! $data->password && app()->environment('local')) {
                $response['initial_password'] = $plainPassword;
            }

            return $response;
        });
    }

    public function updateUser(int $userId, UpdateUserData $data): array
    {
        return DB::transaction(function () use ($userId, $data) {
            $user = User::query()
                ->whereKey($userId)
                ->whereNull('deleted_at')
                ->first();

            if (! $user) {
                throw new BusinessException('ERR_USER_NOT_FOUND', 404);
            }

            $updates = [];
            if ($data->has('employee_code')) {
                $updates['employee_code'] = $data->employee_code;
            }
            if ($data->has('name')) {
                $updates['name'] = $data->name;
            }
            if ($data->has('email')) {
                $updates['email'] = $data->email;
            }
            if ($data->has('phone_number')) {
                $updates['phone_number'] = $data->phone_number;
            }
            if ($data->has('birthday')) {
                $updates['birthday'] = $data->birthday;
            }
            if ($data->has('gender')) {
                $updates['gender'] = $data->gender;
            }
            if ($data->has('join_date')) {
                $updates['join_date'] = $data->join_date;
            }
            if ($data->has('resign_date')) {
                $updates['resign_date'] = $data->resign_date;
            }
            if ($data->has('avatar')) {
                $updates['avatar'] = $data->avatar;
            }
            if ($data->has('is_active')) {
                $updates['is_active'] = $data->is_active;
            }

            // Password: only update when explicitly provided and non-null
            if ($data->has('password') && $data->password !== null && $data->password !== '') {
                $updates['password'] = $data->password;
            }

            if (! empty($updates)) {
                $user->fill($updates);
                $user->save();
            }

            // Positions: sync only when client sends the positions key
            if ($data->has('positions')) {
                $positionsInput = $data->positions ?? [];

                $attach = [];
                foreach ($positionsInput as $pos) {
                    $positionId = $pos['position_id'] ?? null;

                    if (! $positionId && ! empty($pos['position_code'])) {
                        $positionId = Position::where('code', $pos['position_code'])->value('id');
                    }

                    if (! $positionId) {
                        throw new BusinessException('ERR_POSITION_NOT_FOUND', 422);
                    }

                    $attach[$positionId] = [
                        'start_date' => $pos['start_date'] ?? null,
                        'end_date' => $pos['end_date'] ?? null,
                    ];
                }

                $user->positions()->sync($attach);
            }

            $user->load(['positions' => function ($q) {
                $q->select('positions.id', 'positions.code', 'positions.name');
            }]);

            return [
                'id' => $user->id,
                'employee_code' => $user->employee_code,
                'name' => $user->name,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'join_date' => $user->join_date,
                'is_active' => $user->is_active,
                'positions' => $user->positions,
            ];
        });
    }

    public function deleteUser(int $userId): bool
    {
        return DB::transaction(function () use ($userId) {
            $user = User::query()
                ->whereKey($userId)
                ->whereNull('deleted_at')
                ->first();

            if (! $user) {
                throw new BusinessException('ERR_USER_NOT_FOUND', 404);
            }

            $user->delete();

            return true;
        });
    }

    private function generateNextEmployeeCode(): string
    {
        $last = User::where('employee_code', 'like', 'EMP%')
            ->orderBy('employee_code', 'desc')
            ->value('employee_code');

        $next = 1;
        if (is_string($last) && preg_match('/^EMP(\d+)$/', $last, $m)) {
            $next = ((int) $m[1]) + 1;
        }

        return 'EMP'.str_pad((string) $next, 6, '0', STR_PAD_LEFT);
    }
}