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

    protected $casts = [
        'is_active' => 'integer',
        'sort' => 'integer',
    ];

    /**
     * Get the issues for this status.
     */
    public function issues()
    {
        return $this->hasMany(Issue::class, 'status', 'id');
    }

    /**
     * Scope active statuses.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    /**
     * Scope ordered by sort.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort', 'asc');
    }
}
