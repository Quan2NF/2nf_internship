<?php

namespace App\Data\Task;

use Spatie\LaravelData\Data;

/**
 * Data used to post a comment to a task.
 */
class PostCommentToTaskData extends Data
{
    /**
     * @param int $task_id The ID of the task
     * @param string $content The comment content
     */
    public function __construct(
        public int $task_id,
        public string $content,
    ) {}
}
