<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponse
{
    protected function successResponse(
        $data = null,
        string $message = 'Success',
        int $statusCode = 200
    ): JsonResponse {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        if ($data !== null) {
            if ($data instanceof LengthAwarePaginator) {
                $response['data'] = $data->items();
                $response['meta'] = [
                    'current_page' => $data->currentPage(),
                    'from' => $data->firstItem(),
                    'last_page' => $data->lastPage(),
                    'per_page' => $data->perPage(),
                    'to' => $data->lastItem(),
                    'total' => $data->total(),
                ];
            } else {
                $response['data'] = $data;
            }
        }

        return response()->json($response, $statusCode);
    }

    protected function errorResponse(
        string $message = 'An error occurred',
        int $statusCode = 500,
        array $errors = []
    ): JsonResponse {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }

    protected function createdResponse($data, string $message = 'Resource created successfully'): JsonResponse
    {
        return $this->successResponse($data, $message, 201);
    }

    protected function noContentResponse(): JsonResponse
    {
        return response()->json(null, 204);
    }
}