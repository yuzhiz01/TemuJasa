<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $role = $request->user()->role;

        if ($role === 'pelanggan') return redirect()->route('pelanggan.dashboard');
        if ($role === 'penyedia') return redirect()->route('penyedia.dashboard');

        return $next($request);
    }
}
