@extends('voyager::master')

@section('page_title', 'Compose')

@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title"><i class="voyager-paper-plane"></i> {{ $replyTo ? 'Reply' : 'Compose' }}</h1>
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
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul style="margin-bottom: 0;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.mail.send', $account) }}" enctype="multipart/form-data" class="mail-compose-form">
                        @csrf

                        <div class="form-group">
                            <label>{{ __('From') }}</label>
                            <input type="text" class="form-control" value="{{ $account->from_name ? $account->from_name.' <'.$account->email_address.'>' : $account->email_address }}" disabled>
                        </div>

                        <div class="form-group">
                            <label>{{ __('To') }}</label>
                            <input type="email" name="to" class="form-control" required
                                   value="{{ old('to', $replyTo?->from_email) }}">
                        </div>

                        <div class="form-group">
                            <label>{{ __('Subject') }}</label>
                            <input type="text" name="subject" class="form-control" required
                                   value="{{ old('subject', $replyTo ? 'Re: '.preg_replace('/^Re:\s*/i', '', $replyTo->subject) : '') }}">
                        </div>

                        <div class="form-group">
                            <label>{{ __('Message') }}</label>
                            <textarea name="body" class="form-control" rows="12" required>{{ old('body', $replyTo ? "\n\n---- Original message ----\n".($replyTo->body_text ?: strip_tags((string) $replyTo->body_html)) : '') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>{{ __('Attachments') }}</label>
                            <div class="mail-attach-dropzone">
                                <i class="voyager-paperclip"></i>
                                <input type="file" name="attachments[]" multiple style="display: block; margin: 8px auto 0;">
                            </div>
                        </div>

                        @if($replyTo && $replyTo->message_id)
                            <input type="hidden" name="in_reply_to" value="{{ $replyTo->message_id }}">
                        @endif

                        <button type="submit" class="btn btn-success mail-send-btn">
                            <i class="voyager-check"></i> {{ __('Send') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
