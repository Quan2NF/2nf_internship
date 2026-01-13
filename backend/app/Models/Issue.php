<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_id',
        'created_by',
        'assigned_to',
        'title',
        'description',
        'type',
        'priority',
        'status',
    ];
}

