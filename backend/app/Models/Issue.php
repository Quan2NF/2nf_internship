<?php

namespace App\Models;

use App\Enums\IssuePriority;
use App\Enums\IssueStatus;
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
        'status'       => IssueStatus::class,
        'priority'     => IssuePriority::class,
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