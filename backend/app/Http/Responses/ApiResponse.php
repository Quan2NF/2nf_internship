<?php

namespace App\Http\Responses;

use App\Enums\ResponseCode;

class ApiResponse
{
    public int $status;
    public ?ResponseItem $response_item;
    public mixed $data;

    public function __construct(int $status = 200, ?ResponseItem $response_item = null, mixed $data = null)
    {
        $this->status = $status;
        $this->response_item = $response_item;
        $this->data = $data;
    }

    public static function response(ResponseCode $code, ?string $field = null, ?string $value = null, mixed $data = null): self
    {
        $responseItem = self::getResponseItem($code, $field, $value);

        // Extract HTTP status from code (e.g., R_CMN_200_01 → 200)
        preg_match('/R_CMN_(\d{3})_/', $code->value, $matches);
        $status = isset($matches[1]) ? (int)$matches[1] : 200;

        return new self(
            status: $status,
            response_item: $responseItem,
            data: $data
        );
    }

    protected static function getResponseItem(ResponseCode $code, ?string $field = null, ?string $value = null): ?ResponseItem
    {
        $message = $code->message($field, $value);
        if (!$message) return null;
        return new ResponseItem(code: $code->value, message: $message);
    }
}