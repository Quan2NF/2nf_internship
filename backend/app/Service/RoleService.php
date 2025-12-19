<?php

namespace App\Service;

use App\Service\Interfaces\RoleServiceInterface;
use App\Http\Responses\ApiResponse;

class RoleService extends BaseService implements RoleServiceInterface
{
    public function getList(): ApiResponse
    {
        throw new \Exception('Not implemented');
    }
}