<?php

namespace App\Models;

use App\Enums\Project\ProjectStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'description',
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

    protected function casts(): array
    {
        return [
            'status'             => ProjectStatus::class,
            'planned_start_date' => 'date',
            'planned_end_date'   => 'date',
            'start_date'         => 'date',
            'end_date'           => 'date',
            'is_public'          => 'boolean',
            'is_active'          => 'boolean',
        ];
    }

    protected $attributes = [
        'is_public' => false,
        'is_active' => true,
    ];

    // RELATIONSHIPS
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_members')
                    ->withPivot('id')
                    ->withTimestamps();
    }
}
