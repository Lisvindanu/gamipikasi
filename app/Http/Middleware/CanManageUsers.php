<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CanManageUsers
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Allow Lead, Co-Lead, or HR Head (Lisvindanu)
        if (in_array($user->role, ['lead', 'co-lead']) ||
            ($user->role === 'head' && $user->email === 'Lisvindanu015@gmail.com')) {
            return $next($request);
        }

        abort(403, 'Unauthorized');
    }
}
