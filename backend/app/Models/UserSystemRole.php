<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSystemRole extends Model
{
    protected $table = 'user_system_roles';

    protected $fillable = [
        'user_id',
        'role_id',
    ];
}
