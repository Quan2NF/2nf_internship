<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaskType extends Model
{
    use HasFactory;

    protected $table = 'task_types';

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
     * Get the issues for this type.
     */
    public function issues()
    {
        return $this->hasMany(Issue::class, 'type', 'id');
    }

    /**
     * Scope active types.
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

