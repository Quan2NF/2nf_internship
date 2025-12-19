<?php

namespace App\Contracts\Service;

use App\Http\Responses\ApiResponse;

use App\Data\Role\RoleData;

/**
 * @extends BaseServiceInterface<RoleData>
 */
interface RoleServiceInterface extends BaseServiceInterface
{
    public function getList(): ApiResponse;
}