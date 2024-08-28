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

        // Log request details with user information
        Log::info('Request details', [
            'uri' => $request->getUri(),
            'method' => $request->getMethod(),
            'request_body' => $request->all(),
            'response_status' => $response->getStatusCode(),
            'user_email' => $user ? $user->email : 'guest',
            'user_name' => $user ? $user->name : 'guest'
        ]);

        return $response;
    }
}
