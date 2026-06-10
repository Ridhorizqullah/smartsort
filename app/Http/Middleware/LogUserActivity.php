<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Execute the request first so we can optionally log the response status
        $response = $next($request);

        // Hanya log request penting (tidak perlu log GET asset atau hal trivial)
        // Di sini kita log semua request yang bukan GET, ATAU semua request ke route yang dilindungi (punya user)
        $method = $request->method();
        if ($method !== 'GET' || auth()->check()) {
            \Illuminate\Support\Facades\Log::info('User activity', [
                'user_id' => auth()->id() ?? 'guest',
                'role' => auth()->check() ? auth()->user()->role : 'guest',
                'route' => $request->path(),
                'method' => $method,
                'ip_address' => $request->ip(),
                'status_code' => $response->getStatusCode(),
                'user_agent' => $request->userAgent()
            ]);
        }

        return $response;
    }
}
