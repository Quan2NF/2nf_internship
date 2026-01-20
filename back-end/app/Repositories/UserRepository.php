<?php

namespace App\Repositories;

use App\Data\ListUsersData;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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
}