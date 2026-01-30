<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordReset
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (
            $request->user() &&
            $request->user()->force_password_reset &&
            ! $request->routeIs([
                'admin.profile.settings',
                'password.update',
                'logout',
            ])
        ) {
            return redirect()
                ->route('admin.profile.settings')
                ->with('warning', 'You must reset your password before proceeding.');
        }

        return $next($request);
    }
}
