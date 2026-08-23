<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RolePelanggan
{
    public function handle(Request $request, Closure $next)
    {
        $role = $request->user()->role;

        if ($role === 'admin') return redirect()->route('admin.dashboard');
        if ($role === 'penyedia') return redirect()->route('penyedia.dashboard');

        return $next($request);
    }
}
