<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Wiki extends Model
{
    use HasFactory;

    protected $table = 'wikis';

    protected $fillable = [
        'project_id',
    ];

    protected function casts(): array
    {
        return [
            'project_id' => 'integer',
        ];
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
