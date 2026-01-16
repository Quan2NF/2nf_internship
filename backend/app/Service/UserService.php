<?php

namespace App\Service;

use App\Models\User;
use App\Enums\ResponseCode;
use Illuminate\Support\Str;
use App\Data\Common\EntityData;
use App\Data\Common\KeyOnlyData;
use App\Http\Responses\ApiResponse;
use Illuminate\Support\Facades\Hash;
use App\Data\User\UserListFilterData;
use App\Data\Response\ApiResponseData;
use App\Data\User\AssignPositionsToUserData;
use App\Contracts\Service\UserServiceInterface;

class UserService extends BaseService implements UserServiceInterface
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    public function create(EntityData $data): ApiResponseData
    {
        $temporaryPassword = Str::random(12);

        $user = $this->model->create([
            ...$data->toArray(),
            'password' => Hash::make($temporaryPassword),
        ]);

        return ApiResponse::from(ResponseCode::SUCCESS);
    }

    public function getFilteredList(UserListFilterData $data): ApiResponseData
    {
        $query = $this->model->newQuery();

        // Keyword search (name, email)
        if ($data->keyword !== null && $data->keyword !== '') {
            $query->where(function ($q) use ($data) {
                $q->where('name', 'like', '%' . $data->keyword . '%')
                ->orWhere('email', 'like', '%' . $data->keyword . '%');
            });
        }

        // Active / inactive
        if ($data->is_active !== null) {
            $query->where('is_active', $data->is_active);
        }

        // Gender (Enum)
        if ($data->gender !== null) {
            $query->where('gender', $data->gender->value);
        }

        // Join date range
        if ($data->join_from !== null) {
            $query->whereDate('created_at', '>=', $data->join_from);
        }

        if ($data->join_to !== null) {
            $query->whereDate('created_at', '<=', $data->join_to);
        }

        // Pagination
        $users = $query->paginate(
            $data->per_page,
            ['*'],
            'page',
            $data->page
        );

        return ApiResponse::from(ResponseCode::SUCCESS, $users);
    }

    public function assignPositions(AssignPositionsToUserData $data): ApiResponseData
    {
        throw new \Exception('Not implemented');
    }

    public function getPositions(KeyOnlyData $data): ApiResponseData
    {
        throw new \Exception('Not implemented');
    }
}