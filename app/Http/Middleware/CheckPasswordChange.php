<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $excludedRoutes = [
            'password.change',
            'password.change.update',
            'logout'
        ];

        if (auth()->check() &&
            auth()->user()->must_change_password &&
            !in_array($request->route()->getName(), $excludedRoutes)) {

            return redirect()->route('password.change')
                ->with('warning', 'Anda harus mengubah password default sebelum melanjutkan');
        }

        return $next($request);
    }
}
