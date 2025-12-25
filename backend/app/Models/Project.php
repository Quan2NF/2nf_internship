<?php

namespace App\Models;

use App\Enums\Project\ProjectStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
            'code'               => 'string',
            'name'               => 'string',
            'description'        => 'string',
            'status'             => ProjectStatus::class,
            'planned_start_date' => 'date',
            'planned_end_date'   => 'date',
            'start_date'         => 'date',
            'end_date'           => 'date',
            'progress_rate'      => 'integer',
            'is_public'          => 'boolean',
            'is_active'          => 'boolean',
            'created_by'         => 'integer',
            'updated_by'         => 'integer',
        ];
    }

    protected $attributes = [
        'is_public' => false,
        'is_active' => true,
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'project_members')
                    ->withPivot('id')
                    ->withTimestamps();
    }
}
