@extends('voyager::master')

@section('page_title', $account->label.' — '.$folder->display_name)

@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title"><i class="voyager-mail"></i> {{ $account->label }} — {{ $folder->display_name }}</h1>
        @include('admin.mail.partials.refresh-button')
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
                <div class="mail-panel">
                    @if($messages->isEmpty())
                        <div class="mail-empty-state">
                            <i class="voyager-mail"></i>
                            {{ __('No messages in this folder.') }}
                        </div>
                    @else
                        @foreach($messages as $message)
                            @php($isUnread = ! $message->is_read)
                            <a href="{{ route('admin.mail.message.show', ['account' => $account, 'message' => $message]) }}"
                               class="mail-message-row {{ $isUnread ? 'unread' : '' }}">
                                <span class="mail-message-from">{{ $message->from_name ?: $message->from_email }}</span>
                                <span class="mail-message-body">
                                    <span class="mail-message-subject">{{ $message->subject ?: '(no subject)' }}</span>
                                    @if($message->snippet)
                                        <span class="mail-message-snippet"> — {{ $message->snippet }}</span>
                                    @endif
                                </span>
                                @if($message->has_attachments)
                                    <i class="voyager-paperclip mail-attachment-icon"></i>
                                @endif
                                <span class="mail-message-date">{{ optional($message->date)->format('M j, g:ia') }}</span>
                            </a>
                        @endforeach
                    @endif
                </div>
                <div style="margin-top: 12px;">{{ $messages->links() }}</div>
            </div>
        </div>
    </div>
@stop
