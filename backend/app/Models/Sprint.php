<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sprint extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'name',
        'start_date',
        'end_date',
        'goal',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'status'     => 'string',
    ];

    // CONSTANTS

    public const STATUS_PLANNED     = 'planned';
    public const STATUS_ACTIVE      = 'active';
    public const STATUS_COMPLETED   = 'completed';

    public const STATUSES = [
        self::STATUS_PLANNED,
        self::STATUS_ACTIVE,
        self::STATUS_COMPLETED,
    ];

    // RELATIONSHIPS

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
