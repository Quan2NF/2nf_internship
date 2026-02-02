<?php

namespace App\Service;

use App\Models\User;
use App\Enums\ResponseCode;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Http\Responses\ApiResponse;
use Illuminate\Support\Facades\Hash;
use App\Data\User\UserListFilterData;
use App\Data\Response\ApiResponseData;
use App\Data\User\CreateUserResponseData;
use App\Data\User\DetailUserResponseData;
use App\Data\User\AssignPositionsToUserData;
use App\Contracts\Service\UserServiceInterface;
use App\Data\User\UserData;

class UserService implements UserServiceInterface
{
    public function create(UserData $data): ApiResponseData
    {
        $temporaryPassword = Str::random(12);

        $user = User::query()->create([
            ...$data->toArray(),
            'password' => Hash::make($temporaryPassword),
        ]);

        return ApiResponse::from(ResponseCode::SUCCESS, [
            'id' => $user->id,
        ]);
    }

    public function view(User $user): ApiResponseData
    {
        return ApiResponse::from(ResponseCode::SUCCESS, DetailUserResponseData::from($user));
    }

    public function update(User $user, UserData $data): ApiResponseData
    {
        $user->update(
            array_filter(
                $data->toArray(),
                fn ($v) => $v !== null
            )
        );

        return ApiResponse::from(ResponseCode::SUCCESS, DetailUserResponseData::from($user->fresh()));
    }

    public function delete(User $user): ApiResponseData
    {
        $user->delete();
        return ApiResponse::from(ResponseCode::SUCCESS);
    }

    public function getFilteredList(UserListFilterData $data): ApiResponseData
    {
        $query = User::query()

            ->when(
                $data->keyword !== null && $data->keyword !== '',
                fn ($q) => $q->where(fn ($q2) =>
                    $q2->where('name', 'like', "%{$data->keyword}%")
                    ->orWhere('email', 'like', "%{$data->keyword}%")
                )
            )

            ->when(
                $data->is_active !== null,
                fn ($q) => $q->where('is_active', $data->is_active)
            )

            ->when(
                $data->gender !== null,
                fn ($q) => $q->where('gender', $data->gender->value)
            )

            ->when(
                $data->join_from !== null,
                fn ($q) => $q->where('join_date', '>=', $data->join_from)
            )

            ->when(
                $data->join_to !== null,
                fn ($q) => $q->where('join_date', '<=', $data->join_to)
            );

        $users = $query->paginate(
            perPage: $data->per_page,
            page: $data->page
        );

        return ApiResponse::from(ResponseCode::SUCCESS, $users);
    }

    public function assignPositions(User $user, AssignPositionsToUserData $data): ApiResponseData
    {
        return DB::transaction(function () use ($user, $data) {

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

            // Ensure fresh pivot data
            $user->load('positions');

            return ApiResponse::from(
                ResponseCode::SUCCESS,
                $user->positions->map(fn ($position) => [
                    'id'         => $position->id,
                    'name'       => $position->name,
                    'start_date' => $position->pivot->start_date,
                    'end_date'   => $position->pivot->end_date,
                ])
            );
        });
    }

    public function getPositions(User $user)
    {
        return ApiResponse::from(ResponseCode::SUCCESS, $user->positions
            ->map(fn ($position) => [
                'id'         => $position->id,
                'name'       => $position->name,
                'start_date' => $position->pivot->start_date,
                'end_date'   => $position->pivot->end_date,
            ])
        );
    }
}
