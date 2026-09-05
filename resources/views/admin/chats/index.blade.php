@extends('voyager::master')

@section('page_title', 'Chats')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-chat"></i> Live Chat
    </h1>
@endsection

@section('content')
    <div class="page-content browse index container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Visitor</th>
                                    <th>Last message</th>
                                    <th>Status</th>
                                    <th>Unread</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($conversations as $conversation)
                                    <tr>
                                        <td>
                                            <strong>{{ $conversation->name ?: 'Guest' }}</strong>
                                            @if ($conversation->email)
                                                <br><small>{{ $conversation->email }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $conversation->last_message_at?->diffForHumans() ?? '—' }}
                                        </td>
                                        <td>
                                            <span class="label {{ $conversation->status === 'open' ? 'label-success' : 'label-default' }}">
                                                {{ ucfirst($conversation->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($conversation->unread_count > 0)
                                                <span class="label label-danger">{{ $conversation->unread_count }} new</span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.chats.show', $conversation) }}" class="btn btn-sm btn-primary">
                                                Open
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted" style="padding: 2rem;">
                                            No conversations yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                {{ $conversations->links() }}
            </div>
        </div>
    </div>
@endsection
