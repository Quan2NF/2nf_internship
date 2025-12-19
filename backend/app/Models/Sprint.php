<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sprint extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'name',
        'start_date',
        'end_date',
        'goal',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'status'     => 'string',
    ];

    // RELATIONSHIPS

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
