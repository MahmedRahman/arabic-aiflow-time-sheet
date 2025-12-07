<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        // السماح للمستخدمين الذين لديهم دور admin أو employee
        if (!auth()->check() || (!$user->isAdmin() && !$user->isEmployee())) {
            return redirect()->route('login')->with('error', 'ليس لديك صلاحية للوصول إلى هذه الصفحة.');
        }
        
        return $next($request);
    }
}
