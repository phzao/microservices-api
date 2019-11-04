<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * Class Cors
 * @package App\Http\Middleware
 */
class Cors
{
    /**
     * @param         $request
     * @param Closure $next
     *
     * @return ResponseHeaderBag
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Accept, X-XSRF-TOKEN, Access-Control-Allow-Headers, X-Requested-With, Content-Type, X-Auth-Token, Authorization, Origin');

        return $response;
    }
}
