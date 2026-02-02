<?php

namespace App\Contracts\Service;

use App\Models\Position;
use App\Data\Position\PositionData;
use App\Data\Response\ApiResponseData;

interface PositionServiceInterface
{
    public function create(PositionData $data): ApiResponseData;

    public function update(Position $position, PositionData $data): ApiResponseData;

    public function delete(Position $position): ApiResponseData;

    public function getList(): ApiResponseData;
}