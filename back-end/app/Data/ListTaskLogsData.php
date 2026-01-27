<?php
namespace App\Data;

use Spatie\LaravelData\Data;
use Illuminate\Http\Request;

class ListTaskLogsData extends Data
{
    public int $task_id;
    public static function fromRequest(Request $request, int $taskId): self
    {
        return new self(task_id: $taskId);
    }
}
