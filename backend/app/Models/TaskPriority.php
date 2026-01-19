<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskPriority extends Model
{
    protected $table = 'task_priorities';

    protected $fillable = ['name', 'sort', 'is_active'];

    protected $casts = [
        'sort' => 'integer',
        'is_active' => 'integer',
    ];
}
