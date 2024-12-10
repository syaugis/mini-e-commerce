<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        $roleMap = [
            'ADMIN' => \App\Models\User::ROLE_ADMIN,
            'USER' => \App\Models\User::ROLE_USER,
        ];

        if (!isset($roleMap[$role]) || $user->role !== $roleMap[$role]) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
