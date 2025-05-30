<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class BuyerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   public function handle($request, Closure $next){
    if (Auth::check() && Auth::user()->role_id == 3) {
        return $next($request);
    }

    return response()->json(['message' => 'Unauthorized. Buyer only.'], 403);
}


}
