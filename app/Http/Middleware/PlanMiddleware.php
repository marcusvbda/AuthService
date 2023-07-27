<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
use Auth;

class PlanMiddleware extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        if (!Auth::check()) return $next($request);
        $user = Auth::user();
        if (!$user->plan) {
            return redirect()->route('user.choose_plan');
        }
        if ($user->planIsExpired()) {
            return redirect()->route('user.choose_plan');
        }
        return $next($request);
    }
}
