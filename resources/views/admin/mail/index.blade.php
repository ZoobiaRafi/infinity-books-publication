@extends('voyager::master')

@section('page_title', 'Mail')

@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title"><i class="voyager-mail"></i> Mail</h1>
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
                    <div class="mail-empty-state">
                        <i class="voyager-mail"></i>
                        {{ __('Select a mailbox to view its inbox.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
