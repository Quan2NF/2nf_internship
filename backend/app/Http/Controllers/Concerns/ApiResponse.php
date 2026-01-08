<?php

namespace App\Http\Controllers\Concerns;

use App\Data\Common\ApiSuccessResponseData;
use App\Data\Common\ApiErrorResponseData;
use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    protected function success(
        mixed $data = null,
        string $message = 'SUCCESS',
        int $status = 200
    ): JsonResponse {
        return response()->json(
            new ApiSuccessResponseData(
                message: $message,
                data: $data
            ),
            $status
        );
    }

    protected function error(
        string $message,
        int $status = 400,
        array $errors = []
    ): JsonResponse {
        return response()->json(
            new ApiErrorResponseData(
                message: $message,
                errors: $errors
            ),
            $status
        );
    }
}
