<?php

namespace App\Http\Responses;
use App\Enums\ResponseCode;
use Illuminate\Http\JsonResponse;

class ApiResponse {
    public static function success(mixed $data = null): JsonResponse
    {
        $responseCode = ResponseCode::SUCCESS;

        return response()->json([
            'success' => true,
            'code'    => (string) $responseCode->httpStatus(),
            'data'    => $data,
        ], $responseCode->httpStatus());
    }

    public static function error(
        ResponseCode $responseCode,
        string $message
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'code'    => (string) $responseCode->httpStatus(),
            'message' => $message,
        ], $responseCode->httpStatus());
    }

    public static function validation(
        array $errors,
        string $message = 'Validation failed'
    ): JsonResponse {
        $responseCode = ResponseCode::VALIDATION_FAILED;

        return response()->json([
            'success' => false,
            'code'    => (string) $responseCode->httpStatus(),
            'message' => $message,
            'errors'  => $errors,
        ], $responseCode->httpStatus());
    }
    

}