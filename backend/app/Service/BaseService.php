<?php

namespace App\Service;

use App\Http\Responses\ApiResponse;
use App\Service\Interfaces\BaseServiceInterface;
use Spatie\LaravelData\Data;

abstract class BaseService implements BaseServiceInterface
{
    public function create(Data $data): ApiResponse
    {
        throw new \Exception('Not implemented');
    }

    public function edit(Data $data): ApiResponse
    {
        throw new \Exception('Not implemented');
    }

    public function delete(Data $data): ApiResponse
    {
        throw new \Exception('Not implemented');
    }
}