<?php

namespace Database\Seeders;

use App\Models\EmailAccount;
use App\Models\User;
use Illuminate\Database\Seeder;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\DataType;
use TCG\Voyager\Models\Menu;
use TCG\Voyager\Models\MenuItem;
use TCG\Voyager\Models\Permission;
use TCG\Voyager\Models\Role;

/**
 * Registers the `email_accounts` table as a Voyager BREAD, admin-only
 * (permissions are attached to the admin role only - regular users manage
 * their own mail through the staff-facing mail app, never through this
 * BREAD screen). Same approach as VoyagerPortfolioBreadSeeder — scripted
 * equivalent of Tools > BREAD > Add New, safe to re-run.
 */
class VoyagerEmailAccountsBreadSeeder extends Seeder
{
    public function run(): void
    {
        $dataType = DataType::updateOrCreate(
            ['name' => 'email_accounts'],
            [
                'slug' => 'email-accounts',
                'display_name_singular' => 'Email Account',
                'display_name_plural' => 'Email Accounts',
                'icon' => 'voyager-mail',
                'model_name' => EmailAccount::class,
                'policy_name' => null,
                'controller' => null,
                'description' => '',
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => (object) [],
            ]
        );

        $currentFields = collect($this->rows())->pluck('field');

        DataRow::where('data_type_id', $dataType->id)
            ->whereNotIn('field', $currentFields)
            ->delete();

        foreach ($this->rows() as $order => $row) {
            DataRow::updateOrCreate(
                ['data_type_id' => $dataType->id, 'field' => $row['field']],
                array_merge($row, ['data_type_id' => $dataType->id, 'order' => $order + 1])
            );
        }

        $adminRole = Role::where('name', 'admin')->first();

        foreach (['browse', 'read', 'edit', 'add', 'delete'] as $action) {
            $permission = Permission::updateOrCreate([
                'key' => "{$action}_email_accounts",
                'table_name' => 'email_accounts',
            ]);

            if ($adminRole && ! $adminRole->permissions()->where('permission_id', $permission->id)->exists()) {
                $adminRole->permissions()->attach($permission->id);
            }
        }

        // The mail app at /admin/mail sits behind Voyager's own admin.user
        // middleware, which requires 'browse_admin' to enter the panel at
        // all - not just this feature's own permissions. Regular 'user'-role
        // staff need that base permission to reach their inbox; they are
        // NOT given any of the email_accounts BREAD permissions above, so
        // they still can't browse/edit the Email Accounts admin screen
        // itself, only their own inbox (enforced separately in
        // MailController::authorizeAccount()).
        $userRole = Role::where('name', 'user')->first();
        $browseAdmin = Permission::firstOrCreate(['key' => 'browse_admin', 'table_name' => '']);

        if ($userRole && ! $userRole->permissions()->where('permission_id', $browseAdmin->id)->exists()) {
            $userRole->permissions()->attach($browseAdmin->id);
        }

        $adminMenu = Menu::where('name', 'admin')->first();

        if ($adminMenu) {
            MenuItem::updateOrCreate(
                ['menu_id' => $adminMenu->id, 'route' => 'voyager.email-accounts.index'],
                [
                    'title' => 'Email Accounts',
                    'url' => '',
                    'target' => '_self',
                    'icon_class' => 'voyager-mail',
                    'color' => null,
                    'parent_id' => null,
                    'order' => 9,
                    'parameters' => null,
                ]
            );

            // Separate menu entry for the actual webmail app (visible to
            // both admins and regular staff, unlike "Email Accounts" above
            // which only admins have permission to open).
            MenuItem::updateOrCreate(
                ['menu_id' => $adminMenu->id, 'route' => 'admin.mail.index'],
                [
                    'title' => 'Mail',
                    'url' => '',
                    'target' => '_self',
                    'icon_class' => 'voyager-mail',
                    'color' => null,
                    'parent_id' => null,
                    'order' => 2,
                    'parameters' => null,
                ]
            );
        }
    }

    private function rows(): array
    {
        return [
            // Same belongsTo-relationship convention as VoyagerPortfolioBreadSeeder:
            // a plain row carrying the real "user_id" column, plus a
            // "relationship" row (differently named) that renders the dropdown.
            ['field' => 'user_id', 'type' => 'hidden', 'display_name' => 'User (internal)', 'required' => 0, 'browse' => 0, 'read' => 0, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => (object) []],
            ['field' => 'user', 'type' => 'relationship', 'display_name' => 'Linked User', 'required' => 0, 'browse' => 1, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => [
                'model' => User::class,
                'table' => 'users',
                'type' => 'belongsTo',
                'column' => 'user_id',
                'key' => 'id',
                'label' => 'name',
            ]],
            ['field' => 'label', 'type' => 'text', 'display_name' => 'Label', 'required' => 1, 'browse' => 1, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => $this->required(['placeholder' => 'e.g. "Support Inbox"'])],
            ['field' => 'email_address', 'type' => 'text', 'display_name' => 'Email Address', 'required' => 1, 'browse' => 1, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => $this->required(['placeholder' => 'name@infinitebookspublishing.com'])],
            ['field' => 'from_name', 'type' => 'text', 'display_name' => 'From Name', 'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => ['placeholder' => 'Shown to recipients as the sender name']],
            ['field' => 'imap_host', 'type' => 'text', 'display_name' => 'IMAP Host', 'required' => 1, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => $this->required()],
            ['field' => 'imap_port', 'type' => 'number', 'display_name' => 'IMAP Port', 'required' => 1, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => $this->required()],
            ['field' => 'imap_encryption', 'type' => 'select_dropdown', 'display_name' => 'IMAP Encryption', 'required' => 1, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => [
                'default' => 'ssl',
                'options' => ['ssl' => 'SSL/TLS', 'tls' => 'STARTTLS', 'none' => 'None'],
                'validation' => (object) ['rule' => ['required']],
            ]],
            ['field' => 'imap_username', 'type' => 'text', 'display_name' => 'IMAP Username', 'required' => 1, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => $this->required()],
            ['field' => 'imap_password', 'type' => 'encrypted_password', 'display_name' => 'IMAP Password', 'required' => 0, 'browse' => 0, 'read' => 0, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => (object) []],
            ['field' => 'smtp_host', 'type' => 'text', 'display_name' => 'SMTP Host', 'required' => 1, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => $this->required()],
            ['field' => 'smtp_port', 'type' => 'number', 'display_name' => 'SMTP Port', 'required' => 1, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => $this->required()],
            ['field' => 'smtp_encryption', 'type' => 'select_dropdown', 'display_name' => 'SMTP Encryption', 'required' => 1, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => [
                'default' => 'ssl',
                'options' => ['ssl' => 'SSL/TLS', 'tls' => 'STARTTLS', 'none' => 'None'],
                'validation' => (object) ['rule' => ['required']],
            ]],
            ['field' => 'smtp_username', 'type' => 'text', 'display_name' => 'SMTP Username', 'required' => 1, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => $this->required()],
            ['field' => 'smtp_password', 'type' => 'encrypted_password', 'display_name' => 'SMTP Password', 'required' => 0, 'browse' => 0, 'read' => 0, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => (object) []],
            ['field' => 'is_active', 'type' => 'checkbox', 'display_name' => 'Active', 'required' => 0, 'browse' => 1, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => ['default' => 'checked', 'on' => 'Active', 'off' => 'Inactive', 'checked' => true]],
            ['field' => 'last_synced_at', 'type' => 'timestamp', 'display_name' => 'Last Synced At', 'required' => 0, 'browse' => 1, 'read' => 1, 'edit' => 0, 'add' => 0, 'delete' => 0, 'details' => (object) []],
            ['field' => 'last_sync_error', 'type' => 'text_area', 'display_name' => 'Last Sync Error', 'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 0, 'add' => 0, 'delete' => 0, 'details' => (object) []],
            ['field' => 'created_at', 'type' => 'timestamp', 'display_name' => 'Created At', 'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 0, 'add' => 0, 'delete' => 0, 'details' => (object) []],
            ['field' => 'updated_at', 'type' => 'timestamp', 'display_name' => 'Updated At', 'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 0, 'add' => 0, 'delete' => 0, 'details' => (object) []],
        ];
    }

    /**
     * Voyager's `required` column on a data_row is cosmetic only (just an
     * asterisk in the form) — it does NOT get enforced by the store/update
     * validator. Real enforcement needs `details.validation.rule`, which is
     * what this attaches.
     */
    private function required(array $details = []): object
    {
        return (object) array_merge($details, [
            'validation' => (object) ['rule' => ['required']],
        ]);
    }
}
