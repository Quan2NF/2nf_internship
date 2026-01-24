<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'description',
        'settings',
        'status',
        'planned_start_date',
        'planned_end_date',
        'start_date',
        'end_date',
        'progress_rate',
        'is_public',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'planned_start_date' => 'date',
        'planned_end_date' => 'date',
        'settings' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'progress_rate' => 'integer',
        'is_public' => 'integer',
        'is_active' => 'integer',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function members(): HasMany
    {
        return $this->hasMany(ProjectMember::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_members')
            ->withTimestamps();
    }
}
