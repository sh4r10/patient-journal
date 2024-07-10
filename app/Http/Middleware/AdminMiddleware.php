<?php



namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        if (\Auth::check() && \Auth::user()->is_admin==0) {
            return $next($request);
        }

        abort(403, 'Unauthorized action.');
    }
}