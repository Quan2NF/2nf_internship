<?php

namespace App\Service;

use App\Data\Response\ApiResponseData;
use Illuminate\Database\Eloquent\Model;
use App\Contracts\Service\BaseServiceInterface;
use App\Data\Common\EntityData;
use App\Data\Common\EntityWithKeyData;
use App\Data\Common\KeyOnlyData;
use App\Enums\ResponseCode;
use App\Http\Responses\ApiResponse;

abstract class BaseService implements BaseServiceInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function view(KeyOnlyData $data): ApiResponseData
    {
        $model = $this->model->findOrFail($data->id);

        return ApiResponse::from(ResponseCode::SUCCESS);
    }

    public function create(EntityData $data): ApiResponseData
    {
        $model = $this->model->create($data->toArray());

        return ApiResponse::from(ResponseCode::SUCCESS);
    }

    public function edit(EntityWithKeyData $data): ApiResponseData
    {
        $model = $this->model->findOrFail($data->getId());
        $model->update($data->toArray());

        return ApiResponse::from(ResponseCode::SUCCESS);
    }

    public function delete(KeyOnlyData $data): ApiResponseData
    {
        $model = $this->model->findOrFail($data->id);
        $model->delete();

        return ApiResponse::from(ResponseCode::SUCCESS);
    }
}