<?php
namespace App\Data;

use Spatie\LaravelData\Data;
use Illuminate\Http\Request;

class UpdateTaskData extends Data
{
    public ?string $subject = null;
    public ?string $description = null;
    public ?int $status_id = null;
    public ?int $type_id = null;
    public ?int $priority_id = null;
    public ?int $assigned_to = null;
    public ?string $start_date = null;
    public ?string $due_date = null;
    public ?float $estimated_hours = null;
    public ?float $actual_hours = null;
    public ?int $progress_rate = null;
    public ?int $is_private = null;
    public ?string $closed_at = null;

    public static function fromRequest(Request $request): self
    {
        $data = $request->only([
            'subject',
            'description',
            'status_id',
            'type_id',
            'priority_id',
            'assigned_to',
            'start_date',
            'due_date',
            'estimated_hours',
            'actual_hours',
            'progress_rate',
            'is_private',
            'closed_at',
        ]);

        return self::from($data);
    }
}
