<?php

namespace App\Services\Mail;

use App\Models\EmailAccount;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Transport\Dsn;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransportFactory;
use Symfony\Component\Mailer\Transport\Smtp\Stream\SocketStream;

/**
 * Registers a Laravel mailer at runtime for one EmailAccount's SMTP
 * credentials, so Mail::mailer($name)->send(...) works with ordinary
 * Mailable classes exactly like any of the app's static mailers.
 *
 * Built by hand (rather than via config('mail.mailers.x')) because
 * Laravel's mail config has no way to disable TLS certificate hostname
 * verification, which every account here needs - see ImapAccountClient's
 * class doc for why (shared hosting's cert is for the host's own domain,
 * not the customer's).
 */
class DynamicMailer
{
    public static function nameFor(EmailAccount $account): string
    {
        $name = 'account-'.$account->id;

        $scheme = $account->smtp_encryption === 'ssl' ? 'smtps' : 'smtp';

        $factory = new EsmtpTransportFactory();
        $transport = $factory->create(new Dsn(
            $scheme,
            $account->smtp_host,
            $account->smtp_username,
            $account->smtp_password,
            $account->smtp_port,
        ));

        $stream = $transport->getStream();
        if ($stream instanceof SocketStream) {
            $stream->setStreamOptions([
                'ssl' => [
                    'allow_self_signed' => true,
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ]);
        }

        Mail::extend($name, fn () => $transport);
        config(["mail.mailers.{$name}" => ['transport' => $name]]);

        return $name;
    }
}
