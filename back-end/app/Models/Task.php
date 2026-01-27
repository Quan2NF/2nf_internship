<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_id', 'parent_id', 'subject', 'description', 'status_id', 'type_id', 'priority_id', 'assigned_to', 'created_by', 'start_date', 'due_date', 'estimated_hours', 'actual_hours', 'progress_rate', 'is_private', 'closed_at'
    ];
}
