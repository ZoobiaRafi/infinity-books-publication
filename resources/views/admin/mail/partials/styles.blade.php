<style>
    :root {
        --mail-primary: {{ config('voyager.primary_color', '#22A7F0') }};
        --mail-primary-dark: #1a84bf;
        --mail-unread: #f0592b;
        --mail-ink: #2b3648;
        --mail-muted: #8a94a6;
        --mail-border: #e7eaf0;
        --mail-bg: #f7f9fc;
    }

    .mail-app { color: var(--mail-ink); }

    /* ---------- Dashboard widget ---------- */
    .mail-overview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 16px;
    }

    .mail-overview-card {
        background: #fff;
        border: 1px solid var(--mail-border);
        border-radius: 10px;
        padding: 18px 20px;
        box-shadow: 0 1px 3px rgba(20, 30, 60, 0.06);
        transition: transform .15s ease, box-shadow .15s ease;
        position: relative;
        overflow: hidden;
    }

    .mail-overview-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(20, 30, 60, 0.12);
    }

    .mail-overview-card::before {
        content: "";
        position: absolute;
        top: 0; left: 0; bottom: 0;
        width: 4px;
        background: var(--mail-primary);
    }

    .mail-overview-head {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 14px;
    }

    .mail-avatar {
        flex-shrink: 0;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 700;
        font-size: 15px;
    }

    .mail-overview-title {
        font-weight: 700;
        font-size: 14px;
        color: var(--mail-ink);
        line-height: 1.3;
    }

    .mail-overview-sub {
        font-size: 12px;
        color: var(--mail-muted);
    }

    .mail-overview-stats {
        display: flex;
        justify-content: space-between;
        margin-bottom: 14px;
    }

    .mail-stat-num {
        font-size: 22px;
        font-weight: 700;
        line-height: 1;
    }

    .mail-stat-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: var(--mail-muted);
        margin-top: 4px;
    }

    .mail-stat-unread .mail-stat-num { color: var(--mail-unread); }

    .mail-overview-card .btn {
        width: 100%;
    }

    /* ---------- Mail app shell ---------- */
    .mail-panel {
        border: 1px solid var(--mail-border);
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(20, 30, 60, 0.05);
    }

    .mail-panel-heading {
        padding: 12px 16px;
        font-weight: 700;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: .03em;
        color: var(--mail-muted);
        border-bottom: 1px solid var(--mail-border);
        background: #fbfcfe;
    }

    .mail-list-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 11px 16px;
        text-decoration: none;
        color: var(--mail-ink);
        border-bottom: 1px solid var(--mail-border);
        transition: background .12s ease;
    }

    .mail-list-item:last-child { border-bottom: none; }

    .mail-list-item:hover, .mail-list-item:focus {
        background: var(--mail-bg);
        color: var(--mail-ink);
        text-decoration: none;
    }

    .mail-list-item.active {
        background: rgba(34, 167, 240, 0.08);
        box-shadow: inset 3px 0 0 var(--mail-primary);
        font-weight: 700;
    }

    .mail-list-item .mail-badge {
        margin-left: auto;
        background: var(--mail-unread);
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        border-radius: 999px;
        padding: 1px 8px;
        min-width: 20px;
        text-align: center;
    }

    .mail-compose-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 10px;
        margin-bottom: 16px;
        border-radius: 8px;
        background: var(--mail-primary);
        border: none;
        color: #fff;
        font-weight: 700;
        text-decoration: none;
        transition: background .12s ease;
    }

    .mail-compose-btn:hover, .mail-compose-btn:focus {
        background: var(--mail-primary-dark);
        color: #fff;
        text-decoration: none;
    }

    .mail-refresh-btn {
        /* Without these two, Chrome/Edge's automatic dark-mode styling for
           native form controls draws its own dark "control" surface under
           the button's own background - it showed up as a darker box
           hugging just the button's text. color-scheme opts this element
           out of that auto-styling; appearance strips the native control
           chrome so only our own background/border apply. */
        color-scheme: light;
        -webkit-appearance: none;
        appearance: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--mail-primary);
        border: none;
        outline: none;
        box-shadow: none;
        border-radius: 6px;
        padding: 6px 16px;
        color: #fff;
        font-weight: 600;
        font-size: 13px;
        line-height: normal;
        cursor: pointer;
        transition: background .12s ease;
    }

    .mail-refresh-btn .btn-label,
    .mail-refresh-btn i {
        background: none;
        color: inherit;
    }

    .mail-refresh-btn:hover:not(:disabled),
    .mail-refresh-btn:focus:not(:disabled) {
        background: var(--mail-primary-dark);
        color: #fff;
    }

    .mail-refresh-btn:disabled {
        opacity: .7;
        cursor: default;
    }

    .mail-search-form {
        margin-bottom: 16px;
    }

    .mail-search-form .input-group input {
        border-radius: 8px 0 0 8px;
        border-color: var(--mail-border);
    }

    .mail-search-form .input-group-btn .btn {
        border-radius: 0 8px 8px 0;
        border-color: var(--mail-border);
    }

    /* ---------- Message list ---------- */
    .mail-message-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 13px 16px;
        text-decoration: none;
        color: inherit;
        border-bottom: 1px solid var(--mail-border);
        transition: background .12s ease;
    }

    .mail-message-row:last-child { border-bottom: none; }

    .mail-message-row:hover, .mail-message-row:focus {
        background: var(--mail-bg);
        color: inherit;
        text-decoration: none;
    }

    .mail-message-row.unread {
        background: rgba(34, 167, 240, 0.04);
    }

    .mail-message-row.unread::before {
        content: "";
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--mail-primary);
        flex-shrink: 0;
    }

    .mail-message-row:not(.unread)::before {
        content: "";
        width: 8px;
        flex-shrink: 0;
    }

    .mail-message-from {
        width: 170px;
        flex-shrink: 0;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .mail-message-row.unread .mail-message-from,
    .mail-message-row.unread .mail-message-subject {
        font-weight: 700;
    }

    .mail-message-body {
        flex: 1;
        min-width: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .mail-message-subject { color: var(--mail-ink); }

    .mail-message-snippet {
        color: var(--mail-muted);
        font-weight: 400;
    }

    .mail-message-date {
        width: 110px;
        flex-shrink: 0;
        text-align: right;
        font-size: 12px;
        color: var(--mail-muted);
    }

    .mail-attachment-icon {
        flex-shrink: 0;
        color: var(--mail-muted);
    }

    .mail-folder-label {
        display: inline-block;
        background: var(--mail-bg);
        border: 1px solid var(--mail-border);
        border-radius: 6px;
        padding: 2px 8px;
        font-size: 11px;
        color: var(--mail-muted);
    }

    /* ---------- Message view ---------- */
    .mail-message-meta {
        background: var(--mail-bg);
        border: 1px solid var(--mail-border);
        border-radius: 8px;
        padding: 14px 16px;
        margin-bottom: 16px;
        display: flex;
        gap: 14px;
        align-items: flex-start;
    }

    .mail-message-meta-fields { flex: 1; min-width: 0; }

    .mail-message-meta strong { color: var(--mail-ink); }

    .mail-message-meta .mail-muted { color: var(--mail-muted); }

    .mail-attachment-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #fff;
        border: 1px solid var(--mail-border);
        border-radius: 999px;
        padding: 5px 12px 5px 8px;
        margin: 4px 6px 0 0;
        font-size: 12px;
        color: var(--mail-ink);
        text-decoration: none;
        transition: border-color .12s ease;
    }

    .mail-attachment-chip:hover {
        border-color: var(--mail-primary);
        color: var(--mail-ink);
        text-decoration: none;
    }

    .mail-body-frame {
        width: 100%;
        min-height: 420px;
        border: none;
        border-radius: 8px;
    }

    /* ---------- Compose ---------- */
    .mail-compose-form label {
        font-weight: 700;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .03em;
        color: var(--mail-muted);
    }

    .mail-compose-form .form-control {
        border-radius: 8px;
        border-color: var(--mail-border);
        box-shadow: none;
    }

    .mail-compose-form .form-control:focus {
        border-color: var(--mail-primary);
        box-shadow: 0 0 0 3px rgba(34, 167, 240, 0.15);
    }

    .mail-attach-dropzone {
        border: 2px dashed var(--mail-border);
        border-radius: 8px;
        padding: 16px;
        text-align: center;
        color: var(--mail-muted);
        background: var(--mail-bg);
    }

    .mail-send-btn {
        border-radius: 8px;
        padding: 10px 28px;
        font-weight: 700;
    }

    .mail-empty-state {
        padding: 48px 24px;
        text-align: center;
        color: var(--mail-muted);
    }

    .mail-empty-state i {
        font-size: 32px;
        display: block;
        margin-bottom: 10px;
        opacity: .5;
    }
</style>
