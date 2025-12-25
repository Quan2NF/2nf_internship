<?php

namespace App\Models;

use App\Enums\Task\TaskRelationType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaskRelation extends Model
{
    use HasFactory;

    protected $table = 'task_relations';

    protected $fillable = [
        'task_id',
        'related_task_id',
        'relation_type',
    ];

    protected function casts(): array
    {
        return [
            'task_id'         => 'integer',
            'related_task_id' => 'integer',
            'relation_type'   => TaskRelationType::class,
        ];
    }

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function relatedTask()
    {
        return $this->belongsTo(Task::class, 'related_task_id');
    }
}
