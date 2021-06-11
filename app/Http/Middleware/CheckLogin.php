<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if($request->session()->has('login') && $request->session()->get('login') == "Y") {
            $request->sessionUser = array(
                "no" => $request->session()->get('no'),
                "id" => $request->session()->get('id'),
                "name" => $request->session()->get('name'),
                "login" => "Y"
            );
        } else {
            $request->sessionUser = array(
                "login" => "N"
            );
        }

        return $next($request);
    }
}
