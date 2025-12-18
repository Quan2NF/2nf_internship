<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Milestone extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'project_id',
        'name',
        'due_date',
        'status',
    ];

    protected $casts = [
        'due_date' => 'date',
        'status'   => 'string',
    ];

    // CONSTANTS

    public const STATUS_PENDING      = 'pending';
    public const STATUS_IN_PROGRESS  = 'in_progress';
    public const STATUS_DONE         = 'done';

    public const STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_IN_PROGRESS,
        self::STATUS_DONE,
    ];

    // RELATIONSHIPS

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
