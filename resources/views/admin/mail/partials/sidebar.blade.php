@if($accounts->count() > 1)
    <div class="mail-panel" style="margin-bottom: 15px;">
        <div class="mail-panel-heading">{{ __('Mailboxes') }}</div>
        <div>
            @foreach($accounts as $acct)
                @php($hue = crc32($acct->label) % 360)
                <a href="{{ route('admin.mail.inbox', $acct) }}"
                   class="mail-list-item {{ isset($account) && $account->id === $acct->id ? 'active' : '' }}">
                    <span class="mail-avatar" style="width: 28px; height: 28px; font-size: 12px; background: hsl({{ $hue }}, 55%, 48%);">
                        {{ strtoupper(substr($acct->label, 0, 1)) }}
                    </span>
                    {{ $acct->label }}
                    @if($acct->unreadCount() > 0)
                        <span class="mail-badge">{{ $acct->unreadCount() }}</span>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
@endif

@isset($account)
    <a href="{{ route('admin.mail.compose', $account) }}" class="mail-compose-btn">
        <i class="voyager-plus"></i> {{ __('Compose') }}
    </a>

    <form method="GET" action="{{ route('admin.mail.search', $account) }}" class="mail-search-form">
        <div class="input-group">
            <input type="text" name="q" class="form-control" placeholder="{{ __('Search mail...') }}" value="{{ $term ?? '' }}">
            <span class="input-group-btn">
                <button class="btn btn-default" type="submit"><i class="voyager-search"></i></button>
            </span>
        </div>
    </form>

    <div class="mail-panel">
        <div class="mail-panel-heading">{{ __('Folders') }}</div>
        <div>
            @foreach($account->folders as $f)
                @if($f->role === 'drafts')
                    {{-- Local drafts (saved from Compose/Reply, never synced to the
                         real mailbox) - not the same thing as this IMAP folder,
                         which this app never writes into, so it's repurposed as
                         the entry point to the local drafts list instead. --}}
                    @php($draftCount = $account->drafts()->count())
                    <a href="{{ route('admin.mail.drafts', $account) }}"
                       class="mail-list-item {{ request()->routeIs('admin.mail.drafts') ? 'active' : '' }}">
                        <i class="voyager-file-text"></i>
                        {{ $f->display_name }}
                        @if($draftCount > 0)
                            <span class="mail-badge">{{ $draftCount }}</span>
                        @endif
                    </a>
                    @continue
                @endif

                @php($unread = $f->messages()->where('is_read', false)->count())
                <a href="{{ route('admin.mail.folder', ['account' => $account, 'folder' => $f]) }}"
                   class="mail-list-item {{ isset($folder) && $folder->id === $f->id ? 'active' : '' }}">
                    <i class="{{ match($f->role) {
                        'inbox' => 'voyager-mail',
                        'sent' => 'voyager-paper-plane',
                        'trash' => 'voyager-trash',
                        'junk' => 'voyager-warning',
                        'archive' => 'voyager-archive',
                        default => 'voyager-folder',
                    } }}"></i>
                    {{ $f->display_name }}
                    @if($unread > 0)
                        <span class="mail-badge">{{ $unread }}</span>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
@endisset
