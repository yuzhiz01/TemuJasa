<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();
        $role = $user->role;
        $view = in_array($role, ['pelanggan', 'penyedia']) ? $role . '.profil' : 'pelanggan.profil';

        $data = ['user' => $user];

        if ($role === 'pelanggan') {
            $data['totalOrders'] = \App\Models\Order::where('customer_id', $user->id)->count();
            $data['totalReviews'] = \App\Models\Review::where('customer_id', $user->id)->count();
        }

        if ($role === 'penyedia') {
            $data['completedOrders'] = \App\Models\Order::where('provider_id', $user->id)->where('status', 'Selesai')->count();
            $data['revenueThisMonth'] = \App\Models\Order::where('provider_id', $user->id)->where('status', 'Selesai')->whereMonth('created_at', now()->month)->sum('total');
            $data['latestReviews'] = \App\Models\Review::where('provider_id', $user->id)->with('customer')->latest()->take(5)->get();
            $data['latestOrders'] = \App\Models\Order::where('provider_id', $user->id)->where('status', 'Selesai')->latest()->take(5)->get();
        }

        return view($view, $data);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function password(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $request->user()->update(['password' => Hash::make($data['password'])]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}
