<?php

namespace App\Data\Issue;

use Spatie\LaravelData\Data;

class TaskLogData extends Data
{
    public function __construct(
        public int $id,
        public int $taskId,
        public int $userId,
        public string $field,
        public ?string $oldValue,
        public ?string $newValue,
        public ?string $createdAt,
    ) {}
}

