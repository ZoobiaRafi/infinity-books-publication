<div class="chat-widget" id="chat-widget">
  <button class="chat-bubble" id="chat-bubble" aria-label="Open chat" aria-expanded="false">
    <svg class="chat-bubble-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
    <svg class="chat-bubble-close" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"></path><path d="m6 6 12 12"></path></svg>
    <span class="chat-unread-badge" id="chat-unread-badge" hidden>0</span>
  </button>

  <div class="chat-panel" id="chat-panel" aria-hidden="true">
    <div class="chat-panel-head">
      <div>
        <strong>Infinite Books Publishing</strong>
        <span>We usually reply within a few minutes</span>
      </div>
      <button class="chat-panel-close" id="chat-panel-close" aria-label="Close chat">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"></path><path d="m6 6 12 12"></path></svg>
      </button>
    </div>
    <div class="chat-panel-body" id="chat-panel-body">
      <div class="chat-msg admin">
        <div class="chat-bubble-text">Hi! Tell us a bit about your book or question and we'll get back to you.</div>
      </div>
    </div>
    <form class="chat-panel-form" id="chat-panel-form">
      <textarea id="chat-input" rows="1" placeholder="Type a message…" required></textarea>
      <button type="submit" aria-label="Send message">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 2-7 20-4-9-9-4Z"></path><path d="M22 2 11 13"></path></svg>
      </button>
    </form>
  </div>
</div>
