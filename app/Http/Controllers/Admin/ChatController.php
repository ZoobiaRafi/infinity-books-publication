<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\Conversation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function index(): View
    {
        $conversations = Conversation::withCount([
            'messages as unread_count' => function ($q) {
                $q->where('sender', 'visitor')->whereNull('read_at');
            },
        ])
            ->orderByRaw('last_message_at IS NULL')
            ->orderByDesc('last_message_at')
            ->paginate(20);

        return view('admin.chats.index', compact('conversations'));
    }

    public function show(Conversation $conversation): View
    {
        $conversation->load('messages');

        $conversation->messages()
            ->where('sender', 'visitor')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('admin.chats.show', compact('conversation'));
    }

    public function poll(Request $request, Conversation $conversation): JsonResponse
    {
        $after = (int) $request->query('after', 0);

        $messages = $conversation->messages()
            ->when($after, fn ($q) => $q->where('id', '>', $after))
            ->orderBy('id')
            ->get();

        $conversation->messages()
            ->where('sender', 'visitor')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json([
            'messages' => $messages->map(fn (ChatMessage $m) => $this->formatMessage($m)),
        ]);
    }

    public function reply(Request $request, Conversation $conversation): RedirectResponse
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $conversation->messages()->create([
            'sender' => 'admin',
            'body' => $validated['body'],
        ]);

        $conversation->update(['last_message_at' => now()]);

        return back();
    }

    public function close(Conversation $conversation): RedirectResponse
    {
        $conversation->update(['status' => 'closed']);

        return redirect()->route('admin.chats.index')->with('message', 'Conversation closed.')->with('alert-type', 'success');
    }

    /**
     * Lightweight, site-wide summary of unread visitor messages — polled from
     * every admin page (via config('voyager.additional_js')) to drive the
     * desktop notification + sound, independent of which conversation (if
     * any) the admin currently has open.
     */
    public function unreadCheck(): JsonResponse
    {
        $latest = ChatMessage::with('conversation')
            ->where('sender', 'visitor')
            ->whereNull('read_at')
            ->latest('id')
            ->first();

        $count = ChatMessage::where('sender', 'visitor')->whereNull('read_at')->count();

        return response()->json([
            'count' => $count,
            'latest' => $latest ? [
                'id' => $latest->id,
                'conversation_id' => $latest->conversation_id,
                'name' => $latest->conversation->name ?: 'Guest',
                'body' => str($latest->body)->limit(120)->toString(),
            ] : null,
        ]);
    }

    private function formatMessage(ChatMessage $message): array
    {
        return [
            'id' => $message->id,
            'sender' => $message->sender,
            'body' => $message->body,
            'time' => $message->created_at->format('g:i A'),
        ];
    }
}
