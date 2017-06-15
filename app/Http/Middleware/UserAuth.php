<?php

namespace App\Http\Middleware;

use App\Helpers\Helpers;
use App\Http\Controllers\Api\ResultController as Result;
use Closure;

class UserAuth
{
    public function __construct()
    {
        $this->result = new Result();
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->get('token');
        $token = empty($token) ? $request->header('token') : $token;
        $is = Helpers::is_login($token);
        if (!$is)
        {
            return $this->result->setStatusMsg('error')->setStatusCode(401)->setMessage('Token 过期请重新登陆！')->responseError();
        }
        $request->item = $is;
        return $next($request);
    }
}
