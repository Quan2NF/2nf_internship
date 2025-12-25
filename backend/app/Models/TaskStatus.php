<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaskStatus extends Model
{
    use HasFactory;

    protected $table = 'task_statuses';

    protected $fillable = [
        'name',
        'sort',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'name'      => 'string',
            'sort'      => 'integer',
            'is_active' => 'boolean',
        ];
    }

    protected $attributes = [
        'is_active' => true,
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class, 'status_id');
    }
}
