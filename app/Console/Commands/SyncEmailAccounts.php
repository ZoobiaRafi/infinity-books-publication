<?php

namespace App\Console\Commands;

use App\Models\EmailAccount;
use App\Services\Mail\ImapAccountClient;
use Illuminate\Console\Command;

class SyncEmailAccounts extends Command
{
    protected $signature = 'email:sync {account? : Specific EmailAccount ID to sync, otherwise all active accounts}';

    protected $description = 'Sync folders and recent message headers/flags for connected email accounts via IMAP';

    public function handle(): int
    {
        $accounts = $this->argument('account')
            ? EmailAccount::where('id', $this->argument('account'))->get()
            : EmailAccount::where('is_active', true)->get();

        if ($accounts->isEmpty()) {
            $this->warn('No matching active email accounts found.');

            return self::SUCCESS;
        }

        foreach ($accounts as $account) {
            $this->info("Syncing {$account->email_address}...");

            try {
                $client = (new ImapAccountClient($account))->connect();
                $client->syncFolders();

                foreach ($account->folders as $folder) {
                    $count = $client->syncFolderMessages($folder);
                    $this->line("  {$folder->display_name}: {$count} messages");
                }

                $client->disconnect();

                $account->update(['last_synced_at' => now(), 'last_sync_error' => null]);
            } catch (\Throwable $e) {
                $this->error("  Failed: {$e->getMessage()}");
                $account->update(['last_sync_error' => $e->getMessage()]);
            }
        }

        return self::SUCCESS;
    }
}
