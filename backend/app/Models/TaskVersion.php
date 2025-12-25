<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaskVersion extends Model
{
    use HasFactory;

    protected $table = 'task_versions';

    protected $fillable = [
        'task_id',
        'version_id',
    ];

    protected function casts(): array
    {
        return [
            'task_id'    => 'integer',
            'version_id' => 'integer',
        ];
    }

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function version()
    {
        return $this->belongsTo(Version::class, 'version_id');
    }
}
