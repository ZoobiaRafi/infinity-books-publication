<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\Conversation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * Return the current visitor's conversation (if any) and its messages.
     */
    public function poll(Request $request): JsonResponse
    {
        $conversation = $this->findConversation($request);

        if (! $conversation) {
            return response()->json(['conversation_id' => null, 'messages' => []]);
        }

        $after = (int) $request->query('after', 0);

        $messages = $conversation->messages()
            ->when($after, fn ($q) => $q->where('id', '>', $after))
            ->orderBy('id')
            ->get();

        // Visitor is actively polling, so any admin replies they can now see are read.
        $conversation->messages()
            ->where('sender', 'admin')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json([
            'conversation_id' => $conversation->id,
            'messages' => $messages->map(fn (ChatMessage $m) => $this->formatMessage($m)),
        ]);
    }

    /**
     * Accept a new message from the visitor, creating a conversation on first contact.
     */
    public function send(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
        ]);

        $conversation = $this->findConversation($request);

        if (! $conversation) {
            $conversation = Conversation::create([
                'session_id' => $request->session()->getId(),
                'name' => $validated['name'] ?? null,
                'email' => $validated['email'] ?? null,
                'status' => 'open',
            ]);
            $request->session()->put('chat_conversation_id', $conversation->id);
        } elseif (! empty($validated['name']) || ! empty($validated['email'])) {
            $conversation->fill([
                'name' => $validated['name'] ?? $conversation->name,
                'email' => $validated['email'] ?? $conversation->email,
            ])->save();
        }

        $message = $conversation->messages()->create([
            'sender' => 'visitor',
            'body' => $validated['body'],
        ]);

        $conversation->update(['last_message_at' => now(), 'status' => 'open']);

        return response()->json([
            'conversation_id' => $conversation->id,
            'message' => $this->formatMessage($message),
        ]);
    }

    private function findConversation(Request $request): ?Conversation
    {
        $id = $request->session()->get('chat_conversation_id');

        if (! $id) {
            return null;
        }

        return Conversation::where('id', $id)
            ->where('session_id', $request->session()->getId())
            ->first();
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
