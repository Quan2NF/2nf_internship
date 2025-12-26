<?php

namespace App\Service;

use App\Contracts\Service\PositionServiceInterface;
use App\Data\Response\ApiResponseData;

class PositionService extends BaseService implements PositionServiceInterface
{
    public function getList(): ApiResponseData
    {
        throw new \Exception('Not implemented');
    }
}