<?php

namespace App\Data\Issue;

use Spatie\LaravelData\Data;

class TaskCommentData extends Data
{
    public function __construct(
        public int $id,
        public int $taskId,
        public int $userId,
        public string $content,
        public ?string $createdAt,
    ) {}
}

