<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\IssueStatus;
use App\Enums\IssuePriority;
use App\Enums\IssueType;

class Issue extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'description', 'type', 'priority', 'status',
        'assigned_to', 'project_id', 'reported_by', 'estimated_hours',
        'actual_hours', 'due_date', 'resolution', 'is_public', 'is_active',
        'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'status' => IssueStatus::class,
            'type' => IssueType::class,
            'priority' => IssuePriority::class,
            'due_date' => 'date',
            'estimated_hours' => 'decimal:2',
            'actual_hours' => 'decimal:2',
            'is_public' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function taskStatus()
    {
        return $this->belongsTo(TaskStatus::class, 'status', 'id');
    }

    public function taskPriority()
    {
        return $this->belongsTo(TaskPriority::class, 'priority', 'id');
    }

    public function taskType()
    {
        return $this->belongsTo(TaskType::class, 'type', 'id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeWithoutTrashed($query)
    {
        return $query->whereNull('deleted_at');
    }

    public function scopeOnlyTrashed($query)
    {
        return $query->whereNotNull('deleted_at');
    }

    // Accessors
    public function getStatusLabelAttribute(): string
    {
        return $this->status->label();
    }

    public function getPriorityLabelAttribute(): string
    {
        return $this->priority->label();
    }

    public function getTypeLabelAttribute(): string
    {
        return $this->type->label();
    }
}

