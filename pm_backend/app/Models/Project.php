<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'kickoff_date',
        'start_date',
        'end_date',
        'description',
        'status',        // planning, active, completed, closed
        'created_by',    // user_id (admin / PMO)
        'pm_id',         // PM được assign
    ];

    protected $casts = [
        'kickoff_date' => 'date',
        'start_date'   => 'date',
        'end_date'     => 'date',
    ];

    // PM của project
    public function pm()
    {
        return $this->belongsTo(User::class, 'pm_id');
    }

    // Người tạo
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Members của project
    public function members()
    {
        return $this->belongsToMany(User::class, 'project_members')
                    ->withTimestamps();
    }

    // Issues
    public function issues()
    {
        return $this->hasMany(Issue::class);
    }
}
