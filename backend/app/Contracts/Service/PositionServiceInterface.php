<?php

namespace App\Contracts\Service;

use App\Data\Response\ApiResponseData;

interface PositionServiceInterface extends BaseServiceInterface
{
    public function getList(): ApiResponseData;
}