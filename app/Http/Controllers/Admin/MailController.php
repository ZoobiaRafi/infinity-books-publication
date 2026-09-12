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

    public function compose(EmailAccount $account, ?EmailMessage $replyTo = null): View
    {
        $this->authorizeAccount($account);

        if ($replyTo) {
            abort_if($replyTo->email_account_id !== $account->id, 404);
        }

        return view('admin.mail.compose', [
            'accounts' => $this->accessibleAccounts(),
            'account' => $account,
            'replyTo' => $replyTo,
        ]);
    }

    public function send(Request $request, EmailAccount $account): RedirectResponse
    {
        $this->authorizeAccount($account);

        $data = $request->validate([
            'to' => ['required', 'email'],
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'in_reply_to' => ['nullable', 'string'],
            'attachments.*' => ['nullable', 'file', 'max:10240'],
        ]);

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
            toAddress: $data['to'],
            subjectLine: $data['subject'],
            bodyHtml: nl2br(e($data['body'])),
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
