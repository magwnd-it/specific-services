<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    // Admin Middleware rule
    
    public function handle($request, Closure $next) {

        if (Auth::user() &&  Auth::user()->permission == 0) {
            
            return $next($request);
        }

        return redirect('/');
    }
}