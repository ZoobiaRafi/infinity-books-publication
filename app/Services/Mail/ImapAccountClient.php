<?php

namespace App\Services\Mail;

use App\Models\EmailAccount;
use App\Models\EmailAttachment;
use App\Models\EmailFolder;
use App\Models\EmailMessage;
use Illuminate\Support\Str;
use Webklex\PHPIMAP\Client;
use Webklex\PHPIMAP\ClientManager;
use Webklex\PHPIMAP\Folder as RemoteFolder;
use Webklex\PHPIMAP\Message as RemoteMessage;

/**
 * Wraps a single EmailAccount's IMAP connection. Shared cPanel/web-hosting
 * mail servers present a certificate for the host's own shared domain (e.g.
 * "*.web-hosting.com"), never one matching the customer's own mail hostname
 * - confirmed against this project's actual production mailbox during
 * development (the connection is still fully TLS-encrypted; it's a hostname
 * mismatch, not an unencrypted connection or a suspicious server). Hence
 * validate_cert is always false here rather than per-account configurable.
 */
class ImapAccountClient
{
    private Client $client;

    public function __construct(private readonly EmailAccount $account)
    {
        $manager = new ClientManager();

        $this->client = $manager->make([
            'host' => $account->imap_host,
            'port' => $account->imap_port,
            'encryption' => $account->imap_encryption === 'none' ? false : $account->imap_encryption,
            'validate_cert' => false,
            'username' => $account->imap_username,
            'password' => $account->imap_password,
            'protocol' => 'imap',
        ]);
    }

    public function connect(): static
    {
        $this->client->connect();

        return $this;
    }

    /**
     * Discover remote folders and upsert local EmailFolder rows for them.
     * Safe to call repeatedly - never deletes a local folder that no longer
     * exists remotely, since messages already synced into it should stay
     * reachable even if e.g. the remote folder was renamed.
     */
    public function syncFolders(): void
    {
        foreach ($this->client->getFolders(false) as $remote) {
            /** @var RemoteFolder $remote */
            EmailFolder::updateOrCreate(
                ['email_account_id' => $this->account->id, 'name' => $remote->path],
                [
                    'display_name' => $this->prettyFolderName($remote->name),
                    'role' => $this->detectFolderRole($remote->name),
                ]
            );
        }
    }

    private function prettyFolderName(string $rawName): string
    {
        // "INBOX.Sent" -> "Sent", "INBOX" -> "Inbox"
        $leaf = Str::afterLast($rawName, '.');

        return $leaf === 'INBOX' ? 'Inbox' : ucfirst(strtolower($leaf));
    }

    private function detectFolderRole(string $rawName): ?string
    {
        $leaf = strtoupper(Str::afterLast($rawName, '.'));

        return match (true) {
            $leaf === 'INBOX' => 'inbox',
            $leaf === 'SENT' => 'sent',
            $leaf === 'DRAFTS' => 'drafts',
            $leaf === 'TRASH' => 'trash',
            in_array($leaf, ['JUNK', 'SPAM']) => 'junk',
            $leaf === 'ARCHIVE' => 'archive',
            default => null,
        };
    }

    /**
     * Pull the newest $limit messages' headers/flags for one folder and
     * upsert them locally. Deliberately does NOT fetch message bodies here
     * (kept fast and lightweight for frequent scheduled syncing) - a
     * message's body is fetched and cached on first open instead, via
     * fetchBody(). leaveUnread() ensures merely syncing never marks a
     * message as read on the server.
     */
    public function syncFolderMessages(EmailFolder $folder, int $limit = 100): int
    {
        $remote = $this->client->getFolderByPath($folder->name);

        if (! $remote) {
            return 0;
        }

        $messages = $remote->query()->whereAll()->leaveUnread()->limit($limit)->fetchOrderDesc()->get();

        $count = 0;

        foreach ($messages as $message) {
            /** @var RemoteMessage $message */
            $this->upsertMessage($folder, $message);
            $count++;
        }

        return $count;
    }

    private function upsertMessage(EmailFolder $folder, RemoteMessage $message): EmailMessage
    {
        $from = $message->getFrom()->first();
        $flags = $message->getFlags();

        $local = EmailMessage::updateOrCreate(
            ['email_folder_id' => $folder->id, 'uid' => $message->getUid()],
            [
                'email_account_id' => $this->account->id,
                'message_id' => $message->getMessageId()?->toString(),
                'in_reply_to' => $message->getInReplyTo()?->toString(),
                'references' => (string) $message->getReferences(),
                'subject' => (string) $message->getSubject(),
                'from_name' => $from?->personal,
                'from_email' => $from?->mail,
                'to' => $this->addressesToArray($message->getTo()->all()),
                'cc' => $this->addressesToArray($message->getCc()->all()),
                'date' => $message->getDate()?->toDate(),
                'is_read' => $flags->has('seen'),
                'is_flagged' => $flags->has('flagged'),
                'is_answered' => $flags->has('answered'),
                'has_attachments' => $message->hasAttachments(),
            ]
        );

        return $local;
    }

    /**
     * @param  \Webklex\PHPIMAP\Address[]  $addresses
     */
    private function addressesToArray(array $addresses): array
    {
        return collect($addresses)->map(fn ($a) => [
            'name' => $a->personal ?? null,
            'email' => $a->mail ?? null,
        ])->all();
    }

    /**
     * Fetch and cache the body + attachment list for one message, on demand
     * (first time it's opened) rather than during every scheduled sync.
     */
    public function fetchBody(EmailMessage $localMessage): void
    {
        $folder = $this->client->getFolderByPath($localMessage->folder->name);
        $remote = $folder?->query()->whereAll()->leaveUnread()->getMessageByUid($localMessage->uid);

        if (! $remote) {
            return;
        }

        $localMessage->update([
            'body_html' => $remote->hasHTMLBody() ? $remote->getHTMLBody() : null,
            'body_text' => $remote->hasTextBody() ? $remote->getTextBody() : null,
            'snippet' => Str::limit(strip_tags($remote->hasTextBody() ? $remote->getTextBody() : $remote->getHTMLBody()), 200),
            'body_synced' => true,
        ]);

        foreach ($remote->getAttachments() as $attachment) {
            EmailAttachment::updateOrCreate(
                ['email_message_id' => $localMessage->id, 'filename' => $attachment->getName()],
                [
                    'mime_type' => $attachment->getMimeType(),
                    'size' => $attachment->getSize(),
                    'part_number' => (string) $attachment->part_number,
                ]
            );
        }
    }

    /**
     * Download one attachment's raw bytes on demand - attachment content is
     * never pulled during sync, only when the user actually asks to
     * download it.
     */
    public function fetchAttachmentContent(EmailMessage $localMessage, EmailAttachment $attachment): ?string
    {
        $folder = $this->client->getFolderByPath($localMessage->folder->name);
        $remote = $folder?->query()->whereAll()->leaveUnread()->getMessageByUid($localMessage->uid);

        if (! $remote) {
            return null;
        }

        foreach ($remote->getAttachments() as $remoteAttachment) {
            if ($remoteAttachment->getName() === $attachment->filename) {
                return $remoteAttachment->getContent();
            }
        }

        return null;
    }

    public function markRead(EmailMessage $localMessage): void
    {
        $folder = $this->client->getFolderByPath($localMessage->folder->name);
        $remote = $folder?->query()->whereAll()->getMessageByUid($localMessage->uid);
        $remote?->setFlag('Seen');

        $localMessage->update(['is_read' => true]);
    }

    /**
     * Append a raw RFC822 message (the exact bytes Symfony Mailer sent over
     * SMTP - see MailController::send()) into this account's Sent folder,
     * pre-flagged \Seen. Plain SMTP delivery never does this itself; without
     * it, sent mail would never show up anywhere in the webmail UI. Silently
     * does nothing if the account has no folder tagged role=sent (e.g.
     * before the first sync has ever run).
     */
    public function appendToSentFolder(string $rawMessage): void
    {
        $sentFolderName = EmailFolder::where('email_account_id', $this->account->id)
            ->where('role', 'sent')
            ->value('name');

        if (! $sentFolderName) {
            return;
        }

        $folder = $this->client->getFolderByPath($sentFolderName);
        $folder?->appendMessage($rawMessage, ['\\Seen'], now());
    }

    public function disconnect(): void
    {
        $this->client->disconnect();
    }
}
