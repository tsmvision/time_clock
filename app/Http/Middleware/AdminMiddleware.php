<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use Carbon\Carbon;

class AdminMiddleware
{
    /**
     * check if Administrator or standard user.

     */
    public function handle($request, Closure $next)
    {
        if ($request->user()->type != 'A')
        {
            return redirect('home');
        }

        return $next($request);
    }
}
