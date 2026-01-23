<?php

namespace App\Contracts\Service;

use App\Data\Position\PositionData;
use App\Data\Response\ApiResponseData;

interface PositionServiceInterface
{
    public function create(PositionData $data): ApiResponseData;

    public function update(int $id, PositionData $data): ApiResponseData;

    public function getList(): ApiResponseData;
}