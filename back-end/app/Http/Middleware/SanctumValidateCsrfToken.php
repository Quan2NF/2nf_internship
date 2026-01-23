<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken as Middleware;

class SanctumValidateCsrfToken extends Middleware
{
    protected $except = [
        'api/login',
        'api/forgot-password',
        'api/reset-password',
        'api/logout',
        'api/users',
        'api/users/*',
        'api/roles',
        'api/roles/*',
    ];
}
