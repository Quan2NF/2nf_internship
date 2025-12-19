<?php

namespace App\Contracts\Service;

use App\Data\Response\ApiResponseData;

use App\Data\Role\RoleData;

/**
 * @extends BaseServiceInterface<RoleData>
 */
interface RoleServiceInterface extends BaseServiceInterface
{
    public function getList(): ApiResponseData;
}