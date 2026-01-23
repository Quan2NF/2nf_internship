<?php

namespace App\Data\Task;

use Spatie\LaravelData\Data;

/**
 * Data used to post a comment to a task.
 */
class CommentData extends Data
{
    public function __construct(
        public string $content,
    ) {}
}
