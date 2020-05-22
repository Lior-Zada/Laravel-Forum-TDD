<?php

namespace App\Http\Middleware;

use Closure;

class RedirectIfEmailNotConfirmed
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
        if (!auth()->user()->email_verified_at) {
            return redirect('/threads')
                ->with('flash', 'You must confirm your email address before posting.');
        }

        return $next($request);
    }
}
