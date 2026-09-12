<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;

class ComposedEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  array<int, array{path: string, name: string, mime: ?string}>  $attachmentSpecs
     */
    public function __construct(
        public string $fromAddress,
        public ?string $fromName,
        public string $toAddress,
        public string $subjectLine,
        public string $bodyHtml,
        public ?string $inReplyTo = null,
        public array $attachmentSpecs = [],
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address($this->fromAddress, $this->fromName ?: $this->fromAddress),
            to: [$this->toAddress],
            subject: $this->subjectLine,
        );
    }

    public function content(): Content
    {
        return new Content(htmlString: $this->bodyHtml);
    }

    public function headers(): Headers
    {
        return new Headers(
            references: $this->inReplyTo ? [$this->inReplyTo] : [],
            text: $this->inReplyTo ? ['In-Reply-To' => '<'.$this->inReplyTo.'>'] : [],
        );
    }

    public function attachments(): array
    {
        return collect($this->attachmentSpecs)
            ->map(function (array $spec) {
                $attachment = Attachment::fromPath($spec['path'])->as($spec['name']);

                return $spec['mime'] ? $attachment->withMime($spec['mime']) : $attachment;
            })
            ->all();
    }
}
