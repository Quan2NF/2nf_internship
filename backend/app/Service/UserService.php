<?php

namespace App\Service;

use App\Models\Role;
use App\Models\User;
use App\Models\Project;
use App\Enums\ResponseCode;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Models\ProjectMember;
use App\Data\Common\KeyOnlyData;
use App\Http\Responses\ApiResponse;
use Illuminate\Support\Facades\Hash;
use App\Data\User\UserListFilterData;
use App\Data\Response\ApiResponseData;
use App\Data\User\AssignPositionsToUserData;
use App\Data\User\CreateUserRequestData;
use App\Data\User\UpdateUserRequestData;
use App\Data\User\CreateUserResponseData;
use App\Data\User\DetailUserResponseData;
use App\Contracts\Service\UserServiceInterface;

class UserService implements UserServiceInterface
{
    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function create(CreateUserRequestData $data): ApiResponseData
    {
        $temporaryPassword = Str::random(12);

        $user = User::query()->create([
            ...$data->toArray(),
            'password' => Hash::make($temporaryPassword),
        ]);

        return ApiResponse::from(ResponseCode::SUCCESS, new CreateUserResponseData(
            $user->id,
        ));
    }

    public function update(UpdateUserRequestData $data): ApiResponseData
    {
        $user = User::query()->findOrFail($data->id);

        $user->fill(
            array_filter(
                Arr::except($data->toArray(), ['id']),
                fn ($v) => $v !== null
            )
        );

        $user->save();

        return ApiResponse::from(ResponseCode::SUCCESS, DetailUserResponseData::from($user->fresh()));
    }

    public function getFilteredList(UserListFilterData $data): ApiResponseData
    {
        $query = User::query();

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
            $query->where('join_date', '>=', $data->join_from);
        }

        if ($data->join_to !== null) {
            $query->where('join_date', '<=', $data->join_to);
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
        $user = User::query()->findOrFail($data->user_id);

        $user->positions()->syncWithoutDetaching(
            collect($data->position_ids)
                ->mapWithKeys(fn ($id) => [
                    $id => [
                        'start_date' => now()->toDateString(),
                        'end_date'   => null,
                    ],
                ])
                ->all()
        );

        return ApiResponse::from(ResponseCode::SUCCESS, $user->positions
            ->map(fn ($position) => [
                'id'         => $position->id,
                'name'       => $position->name,
                'start_date' => $position->pivot->start_date,
                'end_date'   => $position->pivot->end_date,
        ]));
    }
}
