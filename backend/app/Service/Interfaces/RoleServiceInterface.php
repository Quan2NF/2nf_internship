<?php

namespace App\Service\Interfaces;

use App\Http\Responses\ApiResponse;

use App\Data\Role\RoleData;

interface RoleServiceInterface
{
    public function getList(RoleData $data): ApiResponse;

    public function create(RoleData $data): ApiResponse;

    public function edit(RoleData $data): ApiResponse;

    public function delete(RoleData $data): ApiResponse;
}