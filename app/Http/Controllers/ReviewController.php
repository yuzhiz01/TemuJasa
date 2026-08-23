<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(Request $request): View
    {
        $userId = $request->user()->id;

        // Pesanan Selesai milik user yang belum direview
        $pendingReview = Order::where('customer_id', $userId)
            ->where('status', 'Selesai')
            ->whereNotExists(function ($q) {
                $q->selectRaw(1)->from('reviews')->whereColumn('reviews.order_id', 'orders.id');
            })
            ->latest()
            ->get();

        return view('pelanggan.review', [
            'pendingReview' => $pendingReview,
            'myReviews'     => Review::with(['order', 'provider'])
                ->where('customer_id', $userId)
                ->latest()
                ->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'order_id' => ['required', 'exists:orders,id'],
            'rating'   => ['required', 'integer', 'between:1,5'],
            'body'     => ['nullable', 'string', 'max:1000'],
        ]);

        $order = Order::findOrFail($data['order_id']);
        abort_unless($order->customer_id === $request->user()->id, 403);

        // satu review per pesanan
        abort_if(Review::where('order_id', $order->id)->exists(), 422, 'Pesanan ini sudah direview.');

        Review::create([
            'customer_id' => $request->user()->id,
            'provider_id' => $order->provider_id,
            'service_id'  => $order->service_id,
            'order_id'    => $order->id,
            'rating'      => $data['rating'],
            'body'        => $data['body'] ?? null,
        ]);

        // perbarui cache rating pada jasa
        if ($order->service_id) {
            $agg = Review::where('service_id', $order->service_id)
                ->selectRaw('AVG(rating) as avg_rating, COUNT(*) as review_count')
                ->first();
            \App\Models\Service::where('id', $order->service_id)->update([
                'avg_rating'   => round((float) ($agg->avg_rating ?? 0), 2),
                'review_count' => (int) ($agg->review_count ?? 0),
            ]);
        }

        return back()->with('success', 'Review berhasil disimpan.');
    }
}
