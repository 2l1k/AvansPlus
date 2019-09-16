<?php

namespace App\Http\Middleware;

use Closure;
use Response;

class BorrowerMiddleware
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
        if(!empty(session("borrower_id"))){
           return $next($request);
        }else{
            return Response::make( '', 403 )->header( 'Location', action("IndexController@index") );
        }
        return $next($request);
        //return abort(403);
        //return Response::make( '', 302 )->header( 'Location', action("IndexController@index") );
    }
}
