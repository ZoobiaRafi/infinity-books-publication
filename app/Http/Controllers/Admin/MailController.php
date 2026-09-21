<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ComposedEmail;
use App\Models\EmailAccount;
use App\Models\EmailAttachment;
use App\Models\EmailFolder;
use App\Models\EmailMessage;
use App\Services\Mail\DynamicMailer;
use App\Services\Mail\ImapAccountClient;
use App\Support\HtmlSanitizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

/**
 * Staff-facing webmail app. Access rule throughout: admins may act on any
 * EmailAccount, everyone else only on the single account linked to their
 * own user record (User::emailAccount()) - enforced by authorizeAccount()
 * on every action, not just by what the account picker happens to show.
 */
class MailController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $accounts = $this->accessibleAccounts();

        if ($accounts->count() === 1) {
            return redirect()->route('admin.mail.inbox', $accounts->first());
        }

        return view('admin.mail.index', ['accounts' => $accounts]);
    }

    public function inbox(Request $request, EmailAccount $account, ?EmailFolder $folder = null): View
    {
        $this->authorizeAccount($account);

        $folder ??= $account->folders()->where('role', 'inbox')->first() ?? $account->folders()->first();

        abort_if(! $folder, 404, 'This account has no synced folders yet. Run the email sync first.');
        abort_if($folder->email_account_id !== $account->id, 404);

        $messages = $folder->messages()
            ->orderByDesc('date')
            ->paginate(25)
            ->withQueryString();

        return view('admin.mail.inbox', [
            'accounts' => $this->accessibleAccounts(),
            'account' => $account,
            'folder' => $folder,
            'messages' => $messages,
        ]);
    }

    public function refresh(EmailAccount $account, ?EmailFolder $folder = null): RedirectResponse
    {
        $this->authorizeAccount($account);

        // Refreshing from an open folder (inbox/folder view) only re-syncs
        // that one folder - it's what the user is actually looking at, and
        // skipping the other 6 folders is what makes the button feel instant
        // instead of waiting on a full account sync. Refreshing from
        // somewhere without a folder in view (e.g. the search page) falls
        // back to the full email:sync command, same as the scheduler.
        if ($folder) {
            abort_if($folder->email_account_id !== $account->id, 404);

            try {
                $client = (new ImapAccountClient($account))->connect();
                $client->syncFolderMessages($folder);
                $client->disconnect();

                $account->update(['last_synced_at' => now(), 'last_sync_error' => null]);

                return redirect()->back()->with('success', $folder->display_name.' refreshed.');
            } catch (\Throwable $e) {
                $account->update(['last_sync_error' => $e->getMessage()]);

                return redirect()->back()->with('error', 'Refresh failed: '.$e->getMessage());
            }
        }

        Artisan::call('email:sync', ['account' => $account->id]);

        $account->refresh();

        return $account->last_sync_error
            ? redirect()->back()->with('error', 'Refresh failed: '.$account->last_sync_error)
            : redirect()->back()->with('success', 'Mailbox refreshed.');
    }

    public function search(Request $request, EmailAccount $account): View
    {
        $this->authorizeAccount($account);

        $term = trim((string) $request->query('q', ''));

        $messages = EmailMessage::with('folder')
            ->where('email_account_id', $account->id)
            ->when($term !== '', function ($query) use ($term) {
                $query->where(function ($q) use ($term) {
                    $q->where('subject', 'like', "%{$term}%")
                        ->orWhere('from_name', 'like', "%{$term}%")
                        ->orWhere('from_email', 'like', "%{$term}%")
                        ->orWhere('body_text', 'like', "%{$term}%");
                });
            }, function ($query) {
                // No search term: an empty result set rather than the whole mailbox.
                $query->whereRaw('1 = 0');
            })
            ->orderByDesc('date')
            ->paginate(25)
            ->withQueryString();

        return view('admin.mail.search', [
            'accounts' => $this->accessibleAccounts(),
            'account' => $account,
            'term' => $term,
            'messages' => $messages,
        ]);
    }

    public function show(EmailAccount $account, EmailMessage $message): View
    {
        $this->authorizeAccount($account);
        abort_if($message->email_account_id !== $account->id, 404);

        if (! $message->body_synced) {
            try {
                (new ImapAccountClient($account))->connect()->fetchBody($message);
                $message->refresh();
            } catch (\Throwable $e) {
                // Body stays unsynced; the view falls back to the cached snippet.
            }
        }

        if (! $message->is_read) {
            try {
                (new ImapAccountClient($account))->connect()->markRead($message);
                $message->refresh();
            } catch (\Throwable $e) {
                $message->update(['is_read' => true]);
            }
        }

        return view('admin.mail.show', [
            'accounts' => $this->accessibleAccounts(),
            'account' => $account,
            'folder' => $message->folder,
            'message' => $message,
        ]);
    }

    public function downloadAttachment(EmailAccount $account, EmailMessage $message, EmailAttachment $attachment): Response
    {
        $this->authorizeAccount($account);
        abort_if($message->email_account_id !== $account->id, 404);
        abort_if($attachment->email_message_id !== $message->id, 404);

        $content = (new ImapAccountClient($account))->connect()->fetchAttachmentContent($message, $attachment);

        abort_if($content === null, 404, 'Attachment could not be retrieved from the mail server.');

        return response($content, 200, [
            'Content-Type' => $attachment->mime_type ?: 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="'.addslashes($attachment->filename).'"',
        ]);
    }

    public function compose(Request $request, EmailAccount $account, ?EmailMessage $replyTo = null): View
    {
        $this->authorizeAccount($account);

        $initialBody = '';
        $initialTo = '';
        $initialCc = '';
        $replyAll = $request->boolean('all');

        if ($replyTo) {
            abort_if($replyTo->email_account_id !== $account->id, 404);

            $initialBody = $this->buildQuotedReplyBody($replyTo);

            $recipients = $this->buildReplyRecipients($replyTo, $account, $replyAll);
            $initialTo = implode(', ', $recipients['to']);
            $initialCc = implode(', ', $recipients['cc']);
        }

        return view('admin.mail.compose', [
            'accounts' => $this->accessibleAccounts(),
            'account' => $account,
            'replyTo' => $replyTo,
            'replyAll' => $replyAll,
            'initialBody' => $initialBody,
            'initialTo' => $initialTo,
            'initialCc' => $initialCc,
        ]);
    }

    public function send(Request $request, EmailAccount $account): RedirectResponse
    {
        $this->authorizeAccount($account);

        $data = $request->validate([
            'to' => ['required', 'string'],
            'cc' => ['nullable', 'string'],
            'bcc' => ['nullable', 'string'],
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'in_reply_to' => ['nullable', 'string'],
            'attachments.*' => ['nullable', 'file', 'max:10240'],
        ]);

        $toAddresses = $this->parseAddressList($data['to']);
        $ccAddresses = $this->parseAddressList($data['cc'] ?? '');
        $bccAddresses = $this->parseAddressList($data['bcc'] ?? '');

        if (empty($toAddresses)) {
            return back()->withInput()->withErrors(['to' => 'Enter at least one valid recipient email address.']);
        }

        if (($data['cc'] ?? '') !== '' && empty($ccAddresses)) {
            return back()->withInput()->withErrors(['cc' => 'One or more Cc addresses look invalid.']);
        }

        if (($data['bcc'] ?? '') !== '' && empty($bccAddresses)) {
            return back()->withInput()->withErrors(['bcc' => 'One or more Bcc addresses look invalid.']);
        }

        $attachmentSpecs = collect($request->file('attachments', []))
            ->filter()
            ->map(fn ($file) => [
                'path' => $file->getRealPath(),
                'name' => $file->getClientOriginalName(),
                'mime' => $file->getClientMimeType(),
            ])
            ->all();

        $mailerName = DynamicMailer::nameFor($account);

        // Capture the exact raw MIME bytes Symfony Mailer assembled (final
        // headers, encoded attachments, multipart boundaries) so the same
        // message can be appended to the account's Sent folder below - SMTP
        // delivery alone never puts a copy there.
        $rawSentMessage = null;
        Event::listen(MessageSent::class, function (MessageSent $event) use (&$rawSentMessage) {
            $rawSentMessage = $event->message->toString();
        });

        Mail::mailer($mailerName)->send(new ComposedEmail(
            fromAddress: $account->email_address,
            fromName: $account->from_name,
            toAddresses: $toAddresses,
            subjectLine: $data['subject'],
            bodyHtml: HtmlSanitizer::clean($data['body']),
            ccAddresses: $ccAddresses,
            bccAddresses: $bccAddresses,
            inReplyTo: $data['in_reply_to'] ?? null,
            attachmentSpecs: $attachmentSpecs,
        ));

        if ($rawSentMessage) {
            try {
                (new ImapAccountClient($account))->connect()->appendToSentFolder($rawSentMessage);
            } catch (\Throwable $e) {
                // The send already succeeded; losing the Sent-folder copy isn't
                // worth failing the request over.
            }
        }

        return redirect()->route('admin.mail.inbox', $account)->with('success', 'Email sent.');
    }

    /**
     * Gmail-style quote block for a reply: an empty line to type into, then
     * "On <date>, <sender> wrote:" followed by the original message
     * indented in a blockquote. The original body is a *received* message,
     * so it's untrusted external HTML - sanitized here before it ever
     * touches the compose editor's DOM (the sandboxed iframe on the message
     * view page already protects reading it, but the compose page isn't
     * sandboxed, so this needs its own pass rather than relying on that).
     */
    private function buildQuotedReplyBody(EmailMessage $replyTo): string
    {
        $quoted = $replyTo->body_html
            ? HtmlSanitizer::clean($replyTo->body_html)
            : nl2br(e($replyTo->body_text ?? ''));

        $who = $replyTo->from_name ? "{$replyTo->from_name} <{$replyTo->from_email}>" : $replyTo->from_email;
        $when = $replyTo->date?->format('D, M j, Y \a\t g:i A') ?? '';

        return '<p><br></p><p>On '.e($when).', '.e($who).' wrote:</p>'
            .'<blockquote style="margin:0 0 0 .8ex; border-left:1px solid #ccc; padding-left:1ex;">'.$quoted.'</blockquote>';
    }

    /**
     * Reply defaults the "To" field to just the original sender. Reply All
     * additionally Cc's everyone else who was on the original message (its
     * "To" and "Cc" recipients combined), minus this account's own address
     * so it doesn't end up replying to itself, and de-duplicated.
     *
     * @return array{to: array<int, string>, cc: array<int, string>}
     */
    private function buildReplyRecipients(EmailMessage $replyTo, EmailAccount $account, bool $replyAll): array
    {
        $to = array_filter([$replyTo->from_email]);

        if (! $replyAll) {
            return ['to' => $to, 'cc' => []];
        }

        $self = strtolower($account->email_address);

        $others = collect($replyTo->to ?? [])
            ->merge($replyTo->cc ?? [])
            ->pluck('email')
            ->filter()
            ->reject(fn ($email) => strtolower($email) === $self || in_array(strtolower($email), array_map('strtolower', $to), true))
            ->unique(fn ($email) => strtolower($email))
            ->values()
            ->all();

        return ['to' => $to, 'cc' => $others];
    }

    /**
     * Splits a comma/semicolon-separated recipient field (as typed by a
     * person, e.g. "a@x.com, b@y.com") into a list of validated addresses,
     * silently dropping anything that isn't a well-formed email rather than
     * failing the whole send over one typo'd entry.
     *
     * @return array<int, string>
     */
    private function parseAddressList(string $value): array
    {
        return collect(preg_split('/[,;]+/', $value))
            ->map(fn ($email) => trim($email))
            ->filter()
            ->filter(fn ($email) => filter_var($email, FILTER_VALIDATE_EMAIL) !== false)
            ->unique(fn ($email) => strtolower($email))
            ->values()
            ->all();
    }

    private function accessibleAccounts()
    {
        $user = Auth::user();

        return $user->hasRole('admin')
            ? EmailAccount::orderBy('label')->get()
            : EmailAccount::where('user_id', $user->id)->orderBy('label')->get();
    }

    private function authorizeAccount(EmailAccount $account): void
    {
        $user = Auth::user();

        abort_unless($user->hasRole('admin') || $account->user_id === $user->id, 403);
    }
}
