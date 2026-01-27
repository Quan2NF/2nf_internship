<?php
namespace App\Data;

use Spatie\LaravelData\Data;
use Illuminate\Http\Request;

class CreateTaskCommentData extends Data
{
    public ?int $task_id = null;
    public ?int $user_id = null;
    public ?string $content = null;

    public static function fromRequest(Request $request, int $taskId): self
    {
        $data = [
            'task_id' => $taskId,
            'user_id' => $request->user()?->id ?? null,
            'content' => $request->input('content'),
        ];

        return self::from($data);
    }
}
