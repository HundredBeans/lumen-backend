<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class LoginMiddleware
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
        // Check Authorization header for bearer token
        if ($request->header('Authorization')) {
            // Get token from Authorization header
            $token = explode(" ", $request->header('Authorization'));
            $check =  User::where('token', $token[1])->first();
 
            if (!$check) {
                return response('Token is not valid.', 401);
            } else {
                return $next($request);
            }
        } else {
            return response('Please input Token.', 401);
        }
    }
}
