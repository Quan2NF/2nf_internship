<?php

namespace App\Contracts\Service;

use App\Data\Response\ApiResponseData;
use Spatie\LaravelData\Data;

/**
 * @template T of Data
 */
interface BaseServiceInterface
{
    /**
     * @param T $data
     * @return ApiResponse
     */
    public function create(Data $data): ApiResponseData;

    /**
     * @param T $data
     * @return ApiResponse
     */
    public function edit(Data $data): ApiResponseData;

    /**
     * @param T $data
     * @return ApiResponse
     */
    public function delete(Data $data): ApiResponseData;
}