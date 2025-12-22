<?php

namespace App\Data\Response;

use App\Enums\ResponseCode;
use Spatie\LaravelData\Data;

class ApiResponseData extends Data
{
    public function __construct(
        public int $status,
        public ?ResponseCode $response_code,
        public mixed $data
    ) {}

    public function toResponse($request)
    {
        return response()->json(
            [
                'response_code' => $this->response_code->value,
                'data' => $this->data,
            ],
            $this->status
        );
    }
}
