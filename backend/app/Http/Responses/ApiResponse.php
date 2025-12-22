<?php

namespace App\Http\Responses;

use App\Enums\ResponseCode;
use App\Data\Response\ApiResponseData;

final class ApiResponse
{
    public static function apiResponse(
        ResponseCode $code,
        mixed $data = null
    ): ApiResponseData {
        // Extract HTTP status from code (e.g., R_CMN_200_01 → 200)
        preg_match('/R_CMN_(\d{3})_/', $code->value, $matches);
        $status = isset($matches[1]) ? (int) $matches[1] : 200;

        return new ApiResponseData(
            status: $status,
            response_code: $code,
            data: $data
        );
    }
}
