<?php

namespace App\Contracts\Service;

use App\Data\Common\EntityData;
use App\Data\Common\EntityWithKeyData;
use App\Data\Common\KeyOnlyData;
use App\Data\Response\ApiResponseData;

interface BaseServiceInterface
{
    public function view(KeyOnlyData $data): ApiResponseData;

    public function create(EntityData $data): ApiResponseData;

    public function edit(EntityWithKeyData $data): ApiResponseData;

    public function delete(KeyOnlyData $data): ApiResponseData;
}