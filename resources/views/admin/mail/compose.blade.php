@extends('voyager::master')

@section('page_title', 'Compose')

@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title"><i class="voyager-paper-plane"></i> {{ $replyTo ? ($replyAll ? 'Reply All' : 'Reply') : 'Compose' }}</h1>
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
                            <input type="email" name="to" class="form-control" multiple required
                                   placeholder="name@example.com, another@example.com"
                                   value="{{ old('to', $initialTo) }}">
                        </div>

                        <div class="form-group">
                            <label>{{ __('Cc') }}</label>
                            <input type="email" name="cc" class="form-control" multiple
                                   placeholder="name@example.com, another@example.com"
                                   value="{{ old('cc', $initialCc) }}">
                        </div>

                        <div class="form-group">
                            <label>{{ __('Bcc') }}</label>
                            <input type="email" name="bcc" class="form-control" multiple
                                   placeholder="name@example.com, another@example.com"
                                   value="{{ old('bcc') }}">
                        </div>

                        <div class="form-group">
                            <label>{{ __('Subject') }}</label>
                            <input type="text" name="subject" class="form-control" required
                                   value="{{ old('subject', $replyTo ? 'Re: '.preg_replace('/^Re:\s*/i', '', $replyTo->subject) : '') }}">
                        </div>

                        <div class="form-group">
                            <label>{{ __('Message') }}</label>
                            {{-- No `required` here: TinyMCE hides this textarea and replaces
                                 it with its own UI, and a browser can't show the native
                                 "please fill this out" validation bubble on a hidden field -
                                 it just silently blocks the submit instead. Emptiness is
                                 still enforced server-side (MailController::send()). --}}
                            <textarea name="body" id="mail-compose-body" class="form-control" rows="12">{{ old('body', $initialBody) }}</textarea>
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

@section('css')
    <style>
        /* Blend TinyMCE's own chrome with the rest of the mail app instead of
           its default square-cornered look. */
        .tox-tinymce {
            border-radius: 8px !important;
            border-color: var(--mail-border, #e7eaf0) !important;
        }
        .tox .tox-toolbar__primary {
            background: var(--mail-bg, #f7f9fc) !important;
        }
    </style>
@stop

@section('javascript')
    <script>
        function initMailComposeEditor() {
            tinymce.remove('#mail-compose-body');

            tinymce.init(window.voyagerTinyMCE.getConfig({
                selector: '#mail-compose-body',
                menubar: false,
                min_height: 320,
                plugins: 'link lists',
                // Close approximation of Gmail's compose toolbar: format/size,
                // bold/italic/underline/strike, text/highlight color, alignment,
                // lists, indent, blockquote, link, clear formatting.
                toolbar: 'styleselect | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright | bullist numlist outdent indent | blockquote | link unlink | removeformat',
                branding: false,
            }));
        }

        $(document).ready(initMailComposeEditor);

        // Browsers restoring this page from back/forward cache (bfcache) skip
        // re-running scripts, which otherwise leaves a dead, non-editable
        // TinyMCE instance behind - reinitialize whenever that happens.
        window.addEventListener('pageshow', function (event) {
            if (event.persisted) {
                initMailComposeEditor();
            }
        });
    </script>
@stop
