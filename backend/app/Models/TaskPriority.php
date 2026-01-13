<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaskPriority extends Model
{
    use HasFactory;

    protected $table = 'task_priorities';

    protected $fillable = [
        'name',
        'sort',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort' => 'integer',
    ];

    /**
     * Get the issues for this priority.
     */
    public function issues()
    {
        return $this->hasMany(Issue::class, 'priority', 'id');
    }

    /**
     * Scope active priorities.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope ordered by sort.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort', 'asc');
    }
}
