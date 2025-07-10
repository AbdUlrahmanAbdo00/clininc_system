<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PaginationHeadersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->hasHeader('X-Page')) {
            $request->merge(['page' => $request->header('X-Page')]);
        }

        if ($request->hasHeader('X-Page-Size')) {
            $request->merge(['pageSize' => $request->header('X-Page-Size')]);
        }
        return $next($request);
    }
}
