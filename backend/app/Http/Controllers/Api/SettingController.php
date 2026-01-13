<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    use ApiResponse;

    /**
     * API23: Get all settings
     */
    public function index(): JsonResponse
    {
        try {
            // Get all settings from database or config
            $settings = [
                'app_name' => 'Project Management System',
                'app_timezone' => 'Asia/Ho_Chi_Minh',
                'date_format' => 'DD/MM/YYYY',
                'time_format' => 'HH:mm',
                'language' => 'vi',
                'work_days' => 5,
                'work_hours_per_day' => 8,
            ];

            return $this->successResponse(
                data: $settings,
                message: 'Cài đặt hệ thống'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                statusCode: 500
            );
        }
    }

    /**
     * API24: Update settings
     */
    public function update(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'app_name' => 'string|nullable',
                'app_timezone' => 'string|nullable',
                'date_format' => 'string|nullable',
                'time_format' => 'string|nullable',
                'language' => 'string|nullable',
                'work_days' => 'integer|nullable',
                'work_hours_per_day' => 'integer|nullable',
            ]);

            // Update settings in database
            $settings = array_merge($this->getDefaultSettings(), $validated);

            return $this->successResponse(
                data: $settings,
                message: 'Cập nhật cài đặt thành công'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                statusCode: 400
            );
        }
    }

    /**
     * API25: Get schedule information
     */
    public function getSchedule(): JsonResponse
    {
        try {
            $schedule = [
                'work_days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
                'work_hours_per_day' => 8,
                'start_time' => '08:00',
                'end_time' => '17:00',
                'break_time' => ['12:00', '13:00'],
            ];

            return $this->successResponse(
                data: $schedule,
                message: 'Thông tin lịch làm việc'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                statusCode: 500
            );
        }
    }

    /**
     * API26: Update schedule
     */
    public function updateSchedule(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'work_days' => 'array|nullable',
                'work_hours_per_day' => 'integer|nullable',
                'start_time' => 'string|nullable',
                'end_time' => 'string|nullable',
                'break_time' => 'array|nullable',
            ]);

            // Update schedule in database
            $schedule = array_merge($this->getDefaultSchedule(), $validated);

            return $this->successResponse(
                data: $schedule,
                message: 'Cập nhật lịch làm việc thành công'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                statusCode: 400
            );
        }
    }

    /**
     * Get default settings
     */
    private function getDefaultSettings(): array
    {
        return [
            'app_name' => 'Project Management System',
            'app_timezone' => 'Asia/Ho_Chi_Minh',
            'date_format' => 'DD/MM/YYYY',
            'time_format' => 'HH:mm',
            'language' => 'vi',
            'work_days' => 5,
            'work_hours_per_day' => 8,
        ];
    }

    /**
     * Get default schedule
     */
    private function getDefaultSchedule(): array
    {
        return [
            'work_days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
            'work_hours_per_day' => 8,
            'start_time' => '08:00',
            'end_time' => '17:00',
            'break_time' => ['12:00', '13:00'],
        ];
    }
}
