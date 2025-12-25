<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Version extends Model
{
    use HasFactory;

    protected $table = 'versions';

    protected $fillable = [
        'project_id',
        'name',
        'description',
        'start_date',
        'end_date',
    ];

    protected function casts(): array
    {
        return [
            'project_id'  => 'integer',
            'name'        => 'string',
            'description' => 'string',
            'start_date'  => 'date',
            'end_date'    => 'date',
        ];
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
