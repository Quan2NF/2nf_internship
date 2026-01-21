<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * Notes:
     * - Keep your routes in routes/web.php and still allow Postman testing.
     * - Session-based auth will continue to work (cookies), but these endpoints
     *   won't require CSRF tokens anymore.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/login',
        '/logout',
        '/forgot-password',
        '/reset-password',
    ];
}
