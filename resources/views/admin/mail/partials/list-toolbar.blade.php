<form method="POST" action="{{ route('admin.mail.messages.bulk', $account) }}" id="mail-bulk-form">
    @csrf
</form>

<div class="mail-list-toolbar">
    <input type="checkbox" id="mail-select-all" title="{{ __('Select all on this page') }}">

    <button type="submit" form="mail-bulk-form" name="bulk_action" value="mark_read"
            class="mail-bulk-btn" id="mail-bulk-read-btn" disabled>
        <i class="voyager-check"></i> {{ __('Mark as read') }}
    </button>

    <button type="submit" form="mail-bulk-form" name="bulk_action" value="delete"
            class="mail-bulk-btn mail-bulk-delete" id="mail-bulk-delete-btn" disabled
            data-confirm="{{ __('Remove the selected email(s) from the admin panel? They will stay on the actual mail server and can reappear if the server ever sends them again.') }}">
        <i class="voyager-trash"></i> {{ __('Delete') }}
    </button>

    <span class="mail-bulk-count" id="mail-bulk-count"></span>

    <div class="mail-per-page">
        <label for="mail-per-page-select">{{ __('Per page') }}</label>
        <select id="mail-per-page-select">
            @foreach(\App\Http\Controllers\Admin\MailController::PER_PAGE_OPTIONS as $option)
                <option value="{{ $option }}" @selected($perPage == $option)>{{ $option }}</option>
            @endforeach
        </select>
    </div>
</div>

@once
    @push('javascript')
        <script>
            (function () {
                var selectAll = document.getElementById('mail-select-all');
                var readBtn = document.getElementById('mail-bulk-read-btn');
                var deleteBtn = document.getElementById('mail-bulk-delete-btn');
                var countLabel = document.getElementById('mail-bulk-count');
                var perPageSelect = document.getElementById('mail-per-page-select');

                function rowCheckboxes() {
                    return Array.prototype.slice.call(document.querySelectorAll('.mail-row-checkbox'));
                }

                function refreshToolbar() {
                    var checked = rowCheckboxes().filter(function (cb) { return cb.checked; });
                    var any = checked.length > 0;

                    readBtn.disabled = !any;
                    deleteBtn.disabled = !any;
                    countLabel.textContent = any ? checked.length + ' selected' : '';

                    if (selectAll) {
                        var all = rowCheckboxes();
                        selectAll.checked = all.length > 0 && checked.length === all.length;
                        selectAll.indeterminate = any && checked.length < all.length;
                    }
                }

                document.addEventListener('change', function (event) {
                    if (event.target.classList && event.target.classList.contains('mail-row-checkbox')) {
                        refreshToolbar();
                    }
                });

                if (selectAll) {
                    selectAll.addEventListener('change', function () {
                        rowCheckboxes().forEach(function (cb) { cb.checked = selectAll.checked; });
                        refreshToolbar();
                    });
                }

                if (deleteBtn) {
                    deleteBtn.addEventListener('click', function (event) {
                        if (!confirm(deleteBtn.getAttribute('data-confirm'))) {
                            event.preventDefault();
                        }
                    });
                }

                if (perPageSelect) {
                    perPageSelect.addEventListener('change', function () {
                        var url = new URL(window.location.href);
                        url.searchParams.set('per_page', perPageSelect.value);
                        url.searchParams.delete('page');
                        window.location.href = url.toString();
                    });
                }

                refreshToolbar();
            })();
        </script>
    @endpush
@endonce
