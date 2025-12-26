<?php

namespace App\Models;

use App\Enums\Position\PositionScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Position extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'positions';

    protected $fillable = [
        'code',
        'name',
        'scope',
    ];

    protected function casts(): array
    {
        return [
            'code'  => 'string',
            'name'  => 'string',
            'scope' => PositionScope::class,
        ];
    }

    protected $attributes = [
        'scope' => PositionScope::Project,
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_positions')
                    ->withPivot('start_date', 'end_date')
                    ->withTimestamps();
    }
}
