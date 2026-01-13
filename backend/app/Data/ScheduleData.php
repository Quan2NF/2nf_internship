<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class ScheduleData extends Data
{
    public function __construct(
        public array $workDays,
        public int $workHoursPerDay,
        public string $startTime,
        public string $endTime,
        public array $breakTime,
    ) {}

    public static function fromArray(array $schedule): self
    {
        return new self(
            workDays: $schedule['work_days'] ?? ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
            workHoursPerDay: $schedule['work_hours_per_day'] ?? 8,
            startTime: $schedule['start_time'] ?? '08:00',
            endTime: $schedule['end_time'] ?? '17:00',
            breakTime: $schedule['break_time'] ?? ['12:00', '13:00'],
        );
    }
}

