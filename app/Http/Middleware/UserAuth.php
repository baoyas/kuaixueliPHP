<?php

namespace App\Http\Middleware;

use App\Helpers\Helpers;
use Closure;
use Auth;
use Response;
use Illuminate\Http\Request;

class UserAuth
{
    public function __construct()
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = Auth::authenticate();
        } catch(\Exception $e) {
            return redirect('auth/login');
        }
        $request->item['user'] = $user;
        return $next($request);
    }
}
