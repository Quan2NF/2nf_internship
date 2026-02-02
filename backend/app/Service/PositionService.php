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
    public function getList(): ApiResponseData
    {
        $positions = Position::query()->get();

        return ApiResponse::from(ResponseCode::SUCCESS, $positions);
    }

    public function create(PositionData $data): ApiResponseData
    {
        $position = Position::query()->create($data->toArray());

        return ApiResponse::from(ResponseCode::SUCCESS, ['id' => $position->id]);
    }

    public function update(Position $position, PositionData $data): ApiResponseData
    {
        $position->update(
            array_filter(
                $data->toArray(),
                fn ($v) => $v !== null
            )
        );

        return ApiResponse::from(ResponseCode::SUCCESS, DetailPositionResponseData::from($position->fresh()));
    }

    public function delete(Position $position): ApiResponseData
    {
        $position->delete();
        return ApiResponse::from(ResponseCode::SUCCESS);
    }
}