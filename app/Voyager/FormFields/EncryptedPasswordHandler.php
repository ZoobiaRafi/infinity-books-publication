<?php

namespace App\Voyager\FormFields;

use TCG\Voyager\FormFields\AbstractHandler;

/**
 * Same blank-on-every-load / "leave blank to keep current" UX as Voyager's
 * built-in `password` field type, but for a value that needs to be usable
 * later (IMAP/SMTP auth), not a one-way login password. Voyager's own
 * `password` type always bcrypts on save (see
 * TCG\Voyager\Http\Controllers\ContentTypes\Password), which would make the
 * stored value useless for actually authenticating anywhere. Registering a
 * different codename here means Controller::getContentBasedOnType() falls
 * through to its generic Text handler instead (a plain, unmodified pass-
 * through of whatever was submitted) - the actual encryption, and the
 * "blank submission means don't touch the stored value" rule, are handled
 * by a matching accessor/mutator pair on the model itself (see
 * EmailAccount::setImapPasswordAttribute() and its smtp_password sibling).
 */
class EncryptedPasswordHandler extends AbstractHandler
{
    protected $codename = 'encrypted_password';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('admin.formfields.encrypted-password', [
            'row' => $row,
            'options' => $options,
            'dataType' => $dataType,
            'dataTypeContent' => $dataTypeContent,
        ]);
    }
}
