@extends('voyager::master')

@section('page_title', 'Compose')

@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title"><i class="voyager-paper-plane"></i> {{ $replyTo ? ($replyAll ? 'Reply All' : 'Reply') : 'Compose' }}</h1>
        @if($draft)
            <form method="POST" action="{{ route('admin.mail.draft.delete', ['account' => $account, 'draft' => $draft]) }}" class="btn-add-new" style="display: inline-block;"
                  onsubmit="return confirm('{{ __('Discard this draft? This cannot be undone.') }}');">
                @csrf
                <button type="submit" class="btn btn-default">
                    <i class="voyager-trash"></i> <span>{{ __('Discard Draft') }}</span>
                </button>
            </form>
        @endif
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

                    <form method="POST" action="{{ route('admin.mail.send', $account) }}" enctype="multipart/form-data" class="mail-compose-form" id="mail-compose-form">
                        @csrf

                        <div class="form-group">
                            <label>{{ __('From') }}</label>
                            <input type="text" class="form-control" value="{{ $account->from_name ? $account->from_name.' <'.$account->email_address.'>' : $account->email_address }}" disabled>
                        </div>

                        @php
                            $ccValue = old('cc', $initialCc);
                            $bccValue = old('bcc', $initialBcc);
                        @endphp

                        <div class="form-group">
                            <label>{{ __('To') }}</label>
                            <div class="mail-to-row">
                                <input type="email" name="to" class="form-control" multiple required
                                       placeholder="name@example.com, another@example.com"
                                       value="{{ old('to', $initialTo) }}">
                                <a href="#" id="mail-cc-toggle" class="mail-cc-bcc-toggle" style="{{ $ccValue !== '' ? 'display:none;' : '' }}">{{ __('Cc') }}</a>
                                <a href="#" id="mail-bcc-toggle" class="mail-cc-bcc-toggle" style="{{ $bccValue !== '' ? 'display:none;' : '' }}">{{ __('Bcc') }}</a>
                            </div>
                        </div>

                        <div class="form-group" id="mail-cc-group" style="{{ $ccValue === '' ? 'display:none;' : '' }}">
                            <label>{{ __('Cc') }}</label>
                            <input type="email" name="cc" class="form-control" multiple
                                   placeholder="name@example.com, another@example.com"
                                   value="{{ $ccValue }}">
                        </div>

                        <div class="form-group" id="mail-bcc-group" style="{{ $bccValue === '' ? 'display:none;' : '' }}">
                            <label>{{ __('Bcc') }}</label>
                            <input type="email" name="bcc" class="form-control" multiple
                                   placeholder="name@example.com, another@example.com"
                                   value="{{ $bccValue }}">
                        </div>

                        <div class="form-group">
                            <label>{{ __('Subject') }}</label>
                            <input type="text" name="subject" class="form-control" required
                                   value="{{ old('subject', $initialSubject) }}">
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

                        @if($replyTo)
                            <input type="hidden" name="reply_to_id" value="{{ $replyTo->id }}">
                            <input type="hidden" name="reply_all" value="{{ $replyAll ? 1 : 0 }}">
                        @endif

                        @if($draft)
                            <input type="hidden" name="draft_id" value="{{ $draft->id }}">
                        @endif

                        <button type="submit" class="btn btn-success mail-send-btn">
                            <i class="voyager-check"></i> <span class="btn-label">{{ __('Send') }}</span>
                        </button>

                        <button type="submit" class="btn btn-default mail-draft-btn" id="mail-draft-btn"
                                formaction="{{ route('admin.mail.draft.save', $account) }}" formnovalidate>
                            <i class="voyager-file-text"></i> <span class="btn-label">{{ __('Save as Draft') }}</span>
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

        .mail-to-row {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .mail-to-row input {
            flex: 1;
        }

        .mail-cc-bcc-toggle {
            flex-shrink: 0;
            font-size: 13px;
            color: var(--mail-muted, #8a94a6);
            text-decoration: none;
        }

        .mail-cc-bcc-toggle:hover,
        .mail-cc-bcc-toggle:focus {
            color: var(--mail-primary, #22A7F0);
            text-decoration: underline;
        }

        /* Same fix as .mail-refresh-btn: without these, Chrome/Edge's
           automatic dark-mode styling for native <button> elements draws its
           own dark surface under the label text on top of Bootstrap's own
           green background, showing up as a darker box hugging "Send". */
        .mail-send-btn {
            color-scheme: light;
            -webkit-appearance: none;
            appearance: none;
            outline: none;
            box-shadow: none;
        }

        .mail-send-btn .btn-label,
        .mail-send-btn i {
            background: none;
            color: inherit;
        }

        .mail-draft-btn {
            color-scheme: light;
            -webkit-appearance: none;
            appearance: none;
            outline: none;
            box-shadow: none;
            margin-left: 8px;
        }

        .mail-draft-btn .btn-label,
        .mail-draft-btn i {
            background: none;
            color: inherit;
        }

        .mail-send-btn:disabled,
        .mail-draft-btn:disabled {
            opacity: .7;
            cursor: default;
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

        // Gmail-style Cc/Bcc: hidden behind a small toggle link next to To,
        // until clicked (or already pre-filled, e.g. from Reply All).
        function showMailField(groupId, toggleId) {
            document.getElementById(groupId).style.display = 'block';
            document.getElementById(toggleId).style.display = 'none';
            document.querySelector('#' + groupId + ' input').focus();
        }

        document.getElementById('mail-cc-toggle').addEventListener('click', function (event) {
            event.preventDefault();
            showMailField('mail-cc-group', 'mail-cc-toggle');
        });

        document.getElementById('mail-bcc-toggle').addEventListener('click', function (event) {
            event.preventDefault();
            showMailField('mail-bcc-group', 'mail-bcc-toggle');
        });

        // Disable whichever button was actually clicked (Send vs Save as
        // Draft share one form, distinguished via formaction) and show its
        // own "-ing..." state, so a slow SMTP connection / DB write can't be
        // mistaken for the button doing nothing. event.submitter is the
        // specific <button> that triggered this submit.
        document.getElementById('mail-compose-form').addEventListener('submit', function (event) {
            var btn = event.submitter || document.querySelector('.mail-send-btn');
            var isDraft = btn.id === 'mail-draft-btn';
            btn.disabled = true;
            btn.querySelector('.btn-label').innerText = isDraft ? '{{ __('Saving...') }}' : '{{ __('Sending...') }}';
        });

        window.addEventListener('pageshow', function (event) {
            if (event.persisted) {
                var sendBtn = document.querySelector('.mail-send-btn');
                sendBtn.disabled = false;
                sendBtn.querySelector('.btn-label').innerText = '{{ __('Send') }}';

                var draftBtn = document.getElementById('mail-draft-btn');
                draftBtn.disabled = false;
                draftBtn.querySelector('.btn-label').innerText = '{{ __('Save as Draft') }}';
            }
        });
    </script>
@stop
