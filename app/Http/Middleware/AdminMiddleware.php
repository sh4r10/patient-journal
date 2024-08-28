<?php



namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;



class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        if (\Auth::check() && \Auth::user()->role === 'admin') {
            Log::info('AdminMiddleware passed for user: ' . \Auth::user()->email);
            return $next($request);
        }
    
        Log::warning('AdminMiddleware blocked access for user: ' . (\Auth::check() ? \Auth::user()->email : 'guest'));
        abort(403, 'Unauthorized action.');
    }
    
}
