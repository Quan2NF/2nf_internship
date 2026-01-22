<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $table = 'projects';

    protected $fillable = [
        'code',
        'name',
        'description',
        'status',
        'planned_start_date',
        'planned_end_date',
        'start_date',
        'end_date',
        'progress_rate',
        'is_public',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'planned_start_date' => 'date',
        'planned_end_date'   => 'date',
        'start_date'         => 'date',
        'end_date'           => 'date',
        'progress_rate'      => 'integer',
        'is_public'          => 'integer', 
        'is_active'          => 'integer', 
        'created_by'         => 'integer',
        'updated_by'         => 'integer',
    ];
}
