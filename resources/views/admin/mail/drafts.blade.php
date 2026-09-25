@extends('voyager::master')

@section('page_title', 'Drafts — '.$account->label)

@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title"><i class="voyager-file-text"></i> {{ __('Drafts') }}</h1>
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
                    @if($drafts->isEmpty())
                        <div class="mail-empty-state">
                            <i class="voyager-file-text"></i>
                            {{ __('No drafts saved.') }}
                        </div>
                    @else
                        @foreach($drafts as $draft)
                            <div class="mail-message-row">
                                <a href="{{ route('admin.mail.draft.edit', ['account' => $account, 'draft' => $draft]) }}" class="mail-message-link">
                                    <span class="mail-message-from">{{ $draft->to_addresses ?: __('(no recipient)') }}</span>
                                    <span class="mail-message-body">
                                        <span class="mail-message-subject">{{ $draft->subject ?: '(no subject)' }}</span>
                                    </span>
                                    <span class="mail-message-date">{{ $draft->updated_at->format('M j, g:ia') }}</span>
                                </a>
                                <form method="POST" action="{{ route('admin.mail.draft.delete', ['account' => $account, 'draft' => $draft]) }}"
                                      onsubmit="return confirm('{{ __('Discard this draft? This cannot be undone.') }}');">
                                    @csrf
                                    <button type="submit" class="mail-bulk-btn mail-bulk-delete" title="{{ __('Discard') }}">
                                        <i class="voyager-trash"></i>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div style="margin-top: 12px;">{{ $drafts->links() }}</div>
            </div>
        </div>
    </div>
@stop
