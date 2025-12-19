<?php

namespace App\Service;

use App\Contracts\Service\RoleServiceInterface;
use App\Data\Response\ApiResponseData;

class RoleService extends BaseService implements RoleServiceInterface
{
    public function getList(): ApiResponseData
    {
        throw new \Exception('Not implemented');
    }
}