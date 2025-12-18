<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Issue extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'issue_code',
        'parent_id',
        'description',
        'status',
        'priority',
        'milestone_id',
        'sprint_id',
        'project_id',
        'assignee_id',
        'creator_id',
        'start_date',
        'due_date',
        'estimate_time',
        'actual_time',
    ];

    protected $casts = [
        'start_date'   => 'date',
        'due_date'     => 'date',
        'estimate_time'=> 'decimal:2',
        'actual_time'  => 'decimal:2',
        'status'       => 'string',
        'priority'     => 'string',
    ];

    public const STATUS_OPEN        = 'open';
    public const STATUS_TODO        = 'todo';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_REVIEWING   = 'reviewing';
    public const STATUS_RE_OPEN     = 're_open';
    public const STATUS_DONE        = 'done';
    public const STATUS_CLOSE       = 'close';

    public const STATUSES = [
        self::STATUS_OPEN,
        self::STATUS_TODO,
        self::STATUS_IN_PROGRESS,
        self::STATUS_REVIEWING,
        self::STATUS_RE_OPEN,
        self::STATUS_DONE,
        self::STATUS_CLOSE,
    ];

    // RELATIONSHIPS
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function parent()
    {
        return $this->belongsTo(Issue::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Issue::class, 'parent_id');
    }
}