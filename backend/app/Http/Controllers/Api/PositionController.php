<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Position;
use App\Enums\ResponseCode;
use App\Data\Position\PositionData;
use App\Http\Responses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Contracts\Service\PositionServiceInterface;
use App\Http\Requests\Position\CreatePositionRequest;
use App\Http\Requests\Position\DeletePositionRequest;
use App\Http\Requests\Position\UpdatePositionRequest;

class PositionController extends Controller
{
    public function __construct(
        protected PositionServiceInterface $positionService
    ){}

    public function getList()
    {
        return $this->positionService->getList();
    }

    public function create(CreatePositionRequest $request)
    {
        return $this->positionService->create(PositionData::from($request->validated()));
    }

    public function update(Position $position, UpdatePositionRequest $request)
    {
        return $this->positionService->update($position, PositionData::from($request->validated()));
    }

    public function delete(Position $position, DeletePositionRequest $request)
    {
        return $this->positionService->delete($position);
    }
}
