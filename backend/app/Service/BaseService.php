<?php

namespace App\Service;

use App\Contracts\Service\BaseServiceInterface;
use App\Data\Response\ApiResponseData;
use Spatie\LaravelData\Data;

abstract class BaseService implements BaseServiceInterface
{
    public function create(Data $data): ApiResponseData
    {
        throw new \Exception('Not implemented');
    }

    public function edit(Data $data): ApiResponseData
    {
        throw new \Exception('Not implemented');
    }

    public function delete(Data $data): ApiResponseData
    {
        throw new \Exception('Not implemented');
    }
}