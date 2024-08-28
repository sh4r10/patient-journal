<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class RequestLogger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Retrieve user information if authenticated
        $user = auth()->user(); // or use Auth::user() depending on your setup

        Log::channel('clf')->info('Request processed', [
            'remote_addr' => $request->ip(),
            'user' => auth()->check() ? auth()->user()->email : 'guest',
            'role' => auth()->check() ? auth()->user()->role : '-',
            'method' => $request->method(),
            'url' => $request->getRequestUri(),
            'http_version' => $request->server('SERVER_PROTOCOL'),
            'status' => $response->getStatusCode(),
            'response_size' => strlen($response->getContent()),
        ]);

        return $response;
    }
}
