<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RolePenyedia
{
    public function handle(Request $request, Closure $next)
    {
        $role = $request->user()->role;

        if ($role === 'admin') return redirect()->route('admin.dashboard');
        if ($role === 'pelanggan') return redirect()->route('pelanggan.dashboard');

        return $next($request);
    }
}
