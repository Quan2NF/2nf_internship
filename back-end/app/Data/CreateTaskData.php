<?php
namespace App\Data;

use Spatie\LaravelData\Data;
use Illuminate\Http\Request;

class CreateTaskData extends Data
{
    public ?int $project_id = null;
    public ?int $parent_id = null;
    public string $subject = '';
    public ?string $description = null;
    public int $status_id = 0;
    public int $type_id = 0;
    public int $priority_id = 0;
    public ?int $assigned_to = null;
    public int $created_by = 0;
    public ?string $start_date = null;
    public ?string $due_date = null;
    public ?float $estimated_hours = null;
    public ?float $actual_hours = null;
    public ?int $progress_rate = 0;
    public ?int $is_private = 0;
    public ?string $closed_at = null;

    public static function fromRequest(Request $request): self
    {
        $data = $request->only([
            'project_id', 'parent_id', 'subject', 'description', 'status_id', 'type_id', 'priority_id', 'assigned_to', 'start_date', 'due_date', 'estimated_hours', 'actual_hours', 'progress_rate', 'is_private', 'closed_at'
        ]);
        $data['created_by'] = $request->user()->id ?? $request->input('created_by');
        // Use Spatie Data::from to map array keys to data object properly
        return self::from($data);
    }
}
