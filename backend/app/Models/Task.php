<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Task
 *
 * @property int $id
 * @property int $project_id
 * @property int|null $parent_id
 *
 * @property string $subject
 * @property string|null $description
 *
 * @property int $status_id
 * @property int $type_id
 * @property int $priority_id
 *
 * @property int|null $assigned_to
 * @property int $created_by
 *
 * @property \Illuminate\Support\Carbon|null $start_date
 * @property \Illuminate\Support\Carbon|null $due_date
 *
 * @property string|null $estimated_hours
 * @property string|null $actual_hours
 *
 * @property int $progress_rate
 * @property bool $is_private
 *
 * @property \Illuminate\Support\Carbon|null $closed_at
 *
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 *
 * @property-read \App\Models\Project|null $project
 * @property-read \App\Models\User|null $assignee
 * @property-read \App\Models\User|null $creator
 * @property-read \App\Models\TaskStatus|null $status
 * @property-read \App\Models\TaskType|null $type
 * @property-read \App\Models\TaskPriority|null $priority
 * @property-read Task|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Task> $children
 */
class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tasks';

    protected $fillable = [
        'project_id',
        'parent_id',
        'subject',
        'description',
        'status_id',
        'type_id',
        'priority_id',
        'assigned_to',
        'created_by',
        'start_date',
        'due_date',
        'estimated_hours',
        'actual_hours',
        'progress_rate',
        'is_private',
        'closed_at',
    ];

    protected function casts(): array
    {
        return [
            'project_id'       => 'integer',
            'parent_id'        => 'integer',
            'subject'          => 'string',
            'description'      => 'string',
            'status_id'        => 'integer',
            'type_id'          => 'integer',
            'priority_id'      => 'integer',
            'assigned_to'      => 'integer',
            'created_by'       => 'integer',
            'start_date'       => 'date',
            'due_date'         => 'date',
            'estimated_hours'  => 'decimal:2',
            'actual_hours'     => 'decimal:2',
            'progress_rate'    => 'integer',
            'is_private'       => 'boolean',
            'closed_at'        => 'datetime',
        ];
    }

    protected $attributes = [
        'progress_rate' => 0,
        'is_private'    => false,
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function status()
    {
        return $this->belongsTo(TaskStatus::class, 'status_id');
    }

    public function type()
    {
        return $this->belongsTo(TaskType::class, 'type_id');
    }

    public function priority()
    {
        return $this->belongsTo(TaskPriority::class, 'priority_id');
    }

    public function parent()
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Task::class, 'parent_id');
    }

    public function comments()
    {
        return $this->hasMany(TaskComment::class, 'task_id');
    }
}
