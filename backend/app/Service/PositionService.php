<?php

namespace App\Service;

use App\Models\Position;
use App\Enums\ResponseCode;
use Illuminate\Support\Arr;
use App\Data\Position\PositionData;
use App\Http\Responses\ApiResponse;
use App\Data\Response\ApiResponseData;
use App\Data\Position\DetailPositionResponseData;
use App\Contracts\Service\PositionServiceInterface;

class PositionService implements PositionServiceInterface
{
    public function create(PositionData $data): ApiResponseData
    {
        $position = Position::query()->create($data->toArray());

        return ApiResponse::from(ResponseCode::SUCCESS, ['id' => $position->id]);
    }

    public function update(int $id, PositionData $data): ApiResponseData
    {
        $position = Position::query()->findOrFail($id);

        $position->fill(
            array_filter(
                $data->toArray(),
                fn ($v) => $v !== null
            )
        );

        $position->save();

        return ApiResponse::from(ResponseCode::SUCCESS, DetailPositionResponseData::from($position->fresh()));
    }

    public function getList(): ApiResponseData
    {
        $positions = Position::query()->get();

        return ApiResponse::from(ResponseCode::SUCCESS, $positions);
    }
}