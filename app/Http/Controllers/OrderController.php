<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function customerIndex(Request $request): View
    {
        $orders = Order::where('customer_id', $request->user()->id)->latest()->get();
        return view('pelanggan.pesanan', [
            'orders'        => $orders,
            'countSemua'    => $orders->count(),
            'countMenunggu' => $orders->where('status', 'Menunggu')->count(),
            'countBerjalan' => $orders->where('status', 'Berjalan')->count(),
            'countSelesai'  => $orders->where('status', 'Selesai')->count(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'service_id' => ['required', 'integer', 'exists:services,id'],
            'option_id'  => ['nullable', 'integer'],
            'notes'      => ['nullable', 'string', 'max:1000'],
        ]);

        $service = \App\Models\Service::with('options')->findOrFail($data['service_id']);

        $option = null;
        if (!empty($data['option_id'])) {
            $option = $service->options->firstWhere('id', $data['option_id']);
        }

        $order = Order::create([
            'customer_id'   => $request->user()->id,
            'provider_id'   => $service->provider_id,
            'service_id'    => $service->id,
            'option_id'     => $option?->id,
            'service_name'  => $option?->name ?? $service->title,
            'provider_name' => $service->shop_name,
            'total'         => $option?->price ?? $service->price,
            'status'        => 'Menunggu',
            'notes'         => $data['notes'] ?? null,
        ]);

        return redirect()->route('pelanggan.pesanan.sukses', $order);
    }

    public function success(Request $request, Order $order): View
    {
        abort_unless($order->customer_id === $request->user()->id, 403);

        return view('pelanggan.pesan-sukses', ['order' => $order]);
    }

    public function providerIndex(Request $request): View
    {
        $orders = Order::where('provider_id', $request->user()->id)->latest()->get();
        return view('penyedia.pesanan', [
            'orders'          => $orders,
            'countSemua'      => $orders->count(),
            'countBerjalan'   => $orders->where('status', 'Berjalan')->count(),
            'countSelesai'    => $orders->where('status', 'Selesai')->count(),
        ]);
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        abort_unless($order->provider_id === $request->user()->id, 403);
        $data = $request->validate(['status' => ['required', 'in:Menunggu,Berjalan,Selesai,Dibatalkan']]);
        $order->update($data);

        return back()->with('success', 'Status pesanan diperbarui.');
    }
}
