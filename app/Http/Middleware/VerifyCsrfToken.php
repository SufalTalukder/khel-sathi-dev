<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // '*',
        '/payment_response',
        '/hostel/payment_response',
        '/coaching_camp/update_payment_status_coaching_camp',
    ];
}
