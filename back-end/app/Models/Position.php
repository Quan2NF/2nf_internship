<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'is_admin',
    ];

    protected $casts = [
        'is_admin' => 'boolean',
    ];

    /**
     * Relationship: Position có nhiều users
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_positions')
            ->withPivot(['start_date', 'end_date'])
            ->withTimestamps();
    }
}
