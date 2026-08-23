<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $contact = User::where('id', '!=', $user->id)->where('role', $user->role === 'pelanggan' ? 'penyedia' : 'pelanggan')->first();
        $messages = $contact ? Message::where(fn ($query) => $query->where('sender_id', $user->id)->where('recipient_id', $contact->id))
            ->orWhere(fn ($query) => $query->where('sender_id', $contact->id)->where('recipient_id', $user->id))
            ->oldest()->get() : collect();

        // buka halaman chat = semua pesan masuk ditandai sudah dibaca
        Message::where('recipient_id', $user->id)->whereNull('read_at')->update(['read_at' => now()]);

        return view($user->role . '.chat', compact('contact', 'messages'));
    }

    /** Polling lonceng: pesan chat yang belum dibaca (realtime via AJAX) */
    public function poll(Request $request): JsonResponse
    {
        $unread = Message::with('sender')
            ->where('recipient_id', $request->user()->id)
            ->whereNull('read_at')
            ->latest()
            ->take(8)
            ->get();

        return response()->json([
            'unread' => Message::where('recipient_id', $request->user()->id)->whereNull('read_at')->count(),
            'items'  => $unread->map(fn (Message $m) => [
                'sender' => $m->sender?->name ?? 'Pengguna',
                'body'   => \Illuminate\Support\Str::limit($m->body, 60),
                'time'   => $m->created_at?->diffForHumans(),
            ]),
        ]);
    }

    /** Tandai semua pesan masuk sudah dibaca */
    public function markRead(Request $request): JsonResponse
    {
        Message::where('recipient_id', $request->user()->id)->whereNull('read_at')->update(['read_at' => now()]);

        return response()->json(['ok' => true]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'recipient_id' => ['required', 'exists:users,id'],
            'body' => ['required', 'string', 'max:2000'],
        ]);

        Message::create($data + ['sender_id' => $request->user()->id]);

        return back();
    }
}
