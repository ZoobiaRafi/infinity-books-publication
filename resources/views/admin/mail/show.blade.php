@extends('voyager::master')

@section('page_title', $message->subject ?: '(no subject)')

@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title"><i class="voyager-mail"></i> {{ $message->subject ?: '(no subject)' }}</h1>
        <a href="{{ route('admin.mail.reply', ['account' => $account, 'replyTo' => $message]) }}" class="btn btn-primary btn-add-new">
            <i class="voyager-paper-plane"></i> <span>{{ __('Reply') }}</span>
        </a>
    </div>
@stop

@section('content')
    <div class="page-content container-fluid mail-app">
        @include('admin.mail.partials.styles')
        @include('voyager::alerts')
        @include('admin.mail.partials.flash')
        <div class="row">
            <div class="col-md-4">
                @include('admin.mail.partials.sidebar')
            </div>
            <div class="col-md-8">
                <div class="mail-panel" style="padding: 20px;">
                    @php($hue = crc32($message->from_email ?: 'unknown') % 360)
                    <div class="mail-message-meta">
                        <span class="mail-avatar" style="background: hsl({{ $hue }}, 55%, 48%);">
                            {{ strtoupper(substr($message->from_name ?: $message->from_email ?: '?', 0, 1)) }}
                        </span>
                        <div class="mail-message-meta-fields">
                            <div><strong>{{ $message->from_name ?: $message->from_email }}</strong> <span class="mail-muted">&lt;{{ $message->from_email }}&gt;</span></div>
                            <div class="mail-muted">
                                {{ __('To') }}: @foreach(($message->to ?? []) as $recipient){{ $recipient['name'] ? $recipient['name'].' ' : '' }}&lt;{{ $recipient['email'] }}&gt;@if(!$loop->last), @endif @endforeach
                            </div>
                            <div class="mail-muted">{{ optional($message->date)->format('l, M j, Y \a\t g:ia') }}</div>
                        </div>
                    </div>

                    @if($message->attachments->isNotEmpty())
                        <div style="margin-bottom: 18px;">
                            @foreach($message->attachments as $attachment)
                                <a href="{{ route('admin.mail.attachment.download', ['account' => $account, 'message' => $message, 'attachment' => $attachment]) }}" class="mail-attachment-chip">
                                    <i class="voyager-paperclip"></i> {{ $attachment->filename }}
                                </a>
                            @endforeach
                        </div>
                    @endif

                    @if($message->body_html)
                        <iframe srcdoc="{{ $message->body_html }}" class="mail-body-frame" sandbox></iframe>
                    @elseif($message->body_text)
                        <pre style="white-space: pre-wrap; font-family: inherit;">{{ $message->body_text }}</pre>
                    @else
                        <p class="mail-muted">{{ $message->snippet ?: __('This message has no readable body.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop
