@extends('voyager::master')

@section('page_title', 'Search — '.$account->label)

@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title"><i class="voyager-search"></i> {{ __('Search results for') }} "{{ $term }}"</h1>
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
                    @if($term === '')
                        <div class="mail-empty-state">
                            <i class="voyager-search"></i>
                            {{ __('Enter a search term.') }}
                        </div>
                    @elseif($messages->isEmpty())
                        <div class="mail-empty-state">
                            <i class="voyager-search"></i>
                            {{ __('No messages matched your search.') }}
                        </div>
                    @else
                        @foreach($messages as $message)
                            @php($isUnread = ! $message->is_read)
                            <a href="{{ route('admin.mail.message.show', ['account' => $account, 'message' => $message]) }}"
                               class="mail-message-row {{ $isUnread ? 'unread' : '' }}">
                                <span class="mail-folder-label">{{ $message->folder->display_name }}</span>
                                <span class="mail-message-from">{{ $message->from_name ?: $message->from_email }}</span>
                                <span class="mail-message-body">
                                    <span class="mail-message-subject">{{ $message->subject ?: '(no subject)' }}</span>
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
