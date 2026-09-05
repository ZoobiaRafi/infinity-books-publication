@extends('voyager::master')

@section('page_title', 'Chat with '.($conversation->name ?: 'Guest'))

@section('css')
    <style>
        .chat-thread { background:#fff; border:1px solid #e5e5e5; border-radius:4px; padding:1.5rem; height:480px; overflow-y:auto; margin-bottom:1rem; }
        .chat-msg { display:flex; margin-bottom:1rem; }
        .chat-msg .bubble { max-width:70%; padding:0.65rem 1rem; border-radius:12px; line-height:1.4; }
        .chat-msg.visitor { justify-content:flex-start; }
        .chat-msg.visitor .bubble { background:#f1f1f4; color:#333; border-bottom-left-radius:2px; }
        .chat-msg.admin { justify-content:flex-end; }
        .chat-msg.admin .bubble { background:#22A7F0; color:#fff; border-bottom-right-radius:2px; }
        .chat-msg .meta { display:block; font-size:11px; opacity:0.65; margin-top:0.25rem; }
        .chat-reply-form textarea { resize:vertical; }
    </style>
@endsection

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-chat"></i> {{ $conversation->name ?: 'Guest' }}
        @if ($conversation->email)
            <small class="text-muted">{{ $conversation->email }}</small>
        @endif
    </h1>
    <div class="page-title-actions">
        <a href="{{ route('admin.chats.index') }}" class="btn btn-sm btn-default">&larr; All conversations</a>
        @if ($conversation->status === 'open')
            <form action="{{ route('admin.chats.close', $conversation) }}" method="POST" style="display:inline-block;">
                @csrf
                <button type="submit" class="btn btn-sm btn-warning">Mark Closed</button>
            </form>
        @endif
    </div>
@endsection

@section('content')
    <div class="page-content container-fluid" id="chat-app" data-conversation-id="{{ $conversation->id }}" data-poll-url="{{ route('admin.chats.poll', $conversation) }}">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="chat-thread" id="chat-thread">
                    @foreach ($conversation->messages as $message)
                        <div class="chat-msg {{ $message->sender }}">
                            <div class="bubble">
                                {{ $message->body }}
                                <span class="meta">{{ $message->created_at->format('g:i A') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <form class="chat-reply-form" action="{{ route('admin.chats.reply', $conversation) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <textarea name="body" rows="3" class="form-control" placeholder="Type your reply…" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Send Reply</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
    (function () {
        var app = document.getElementById('chat-app');
        var thread = document.getElementById('chat-thread');
        var pollUrl = app.dataset.pollUrl;
        var lastId = 0;

        Array.prototype.forEach.call(thread.querySelectorAll('.chat-msg'), function () {});
        var bubbles = thread.querySelectorAll('.bubble');
        // Track the highest message id already rendered server-side via a data attribute trick:
        lastId = {{ $conversation->messages->max('id') ?? 0 }};

        thread.scrollTop = thread.scrollHeight;

        function appendMessage(m) {
            var wrap = document.createElement('div');
            wrap.className = 'chat-msg ' + m.sender;
            wrap.innerHTML = '<div class="bubble">' + escapeHtml(m.body) + '<span class="meta">' + m.time + '</span></div>';
            thread.appendChild(wrap);
            thread.scrollTop = thread.scrollHeight;
        }

        function escapeHtml(str) {
            var div = document.createElement('div');
            div.textContent = str;
            return div.innerHTML;
        }

        function poll() {
            fetch(pollUrl + '?after=' + lastId, { headers: { 'Accept': 'application/json' } })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    (data.messages || []).forEach(function (m) {
                        appendMessage(m);
                        lastId = m.id;
                    });
                })
                .catch(function () {});
        }

        setInterval(poll, 4000);
    })();
    </script>
@endsection
