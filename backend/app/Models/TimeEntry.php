<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TimeEntry extends Model
{
    use HasFactory;

    protected $table = 'time_entries';

    protected $fillable = [
        'task_id',
        'user_id',
        'spent_on',
        'hours',
        'comment',
    ];

    protected function casts(): array
    {
        return [
            'task_id'  => 'integer',
            'user_id'  => 'integer',
            'spent_on' => 'date',
            'hours'    => 'decimal:2',
            'comment'  => 'string',
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
