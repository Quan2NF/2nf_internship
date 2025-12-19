<?php

namespace App\Contracts\Service;

use App\Http\Responses\ApiResponse;
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
    public function create(Data $data): ApiResponse;

    /**
     * @param T $data
     * @return ApiResponse
     */
    public function edit(Data $data): ApiResponse;

    /**
     * @param T $data
     * @return ApiResponse
     */
    public function delete(Data $data): ApiResponse;
}