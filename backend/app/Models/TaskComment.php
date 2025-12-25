<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaskComment extends Model
{
    use HasFactory;

    protected $table = 'task_comments';

    protected $fillable = [
        'task_id',
        'user_id',
        'content',
    ];

    protected function casts(): array
    {
        return [
            'task_id' => 'integer',
            'user_id' => 'integer',
            'content' => 'string',
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
