<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaskLog extends Model
{
    use HasFactory;

    protected $table = 'task_logs';

    protected $fillable = [
        'task_id',
        'user_id',
        'field',
        'old_value',
        'new_value',
    ];

    protected function casts(): array
    {
        return [
            'task_id'   => 'integer',
            'user_id'   => 'integer',
            'field'     => 'string',
            'old_value' => 'string',
            'new_value' => 'string',
        ];
    }

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
