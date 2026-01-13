<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class SettingData extends Data
{
    public function __construct(
        public string $appName,
        public string $appTimezone,
        public string $dateFormat,
        public string $timeFormat,
        public string $language,
        public int $workDays,
        public int $workHoursPerDay,
    ) {}

    public static function fromArray(array $settings): self
    {
        return new self(
            appName: $settings['app_name'] ?? 'Project Management System',
            appTimezone: $settings['app_timezone'] ?? 'Asia/Ho_Chi_Minh',
            dateFormat: $settings['date_format'] ?? 'DD/MM/YYYY',
            timeFormat: $settings['time_format'] ?? 'HH:mm',
            language: $settings['language'] ?? 'vi',
            workDays: $settings['work_days'] ?? 5,
            workHoursPerDay: $settings['work_hours_per_day'] ?? 8,
        );
    }
}

