<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskType extends Model
{
    protected $table = 'task_types';

    protected $fillable = ['name', 'sort', 'is_active'];

    protected $casts = [
        'sort' => 'integer',
        'is_active' => 'integer',
    ];
}
