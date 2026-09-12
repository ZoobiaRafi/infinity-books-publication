<style>
    .mail-refresh-spinner {
        display: none;
        width: 14px;
        height: 14px;
        margin-left: 8px;
        border: 2px solid rgba(255, 255, 255, 0.5);
        border-top-color: #fff;
        border-radius: 50%;
        vertical-align: middle;
        animation: mail-refresh-spin 0.6s linear infinite;
    }

    @keyframes mail-refresh-spin {
        to { transform: rotate(360deg); }
    }
</style>

<form method="POST" action="{{ route('admin.mail.refresh', ['account' => $account, 'folder' => $folder ?? null]) }}" class="btn-add-new" style="display: inline-block;"
      onsubmit="
          this.querySelector('button').disabled = true;
          this.querySelector('.btn-label').innerText = '{{ __('Refreshing...') }}';
          this.querySelector('.mail-refresh-spinner').style.display = 'inline-block';
      ">
    @csrf
    <button type="submit" class="mail-refresh-btn">
        <i class="voyager-refresh"></i>
        <span class="btn-label">{{ __('Refresh') }}</span>
        <span class="mail-refresh-spinner"></span>
    </button>
</form>
