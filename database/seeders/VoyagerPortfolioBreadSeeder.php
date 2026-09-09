<?php

namespace Database\Seeders;

use App\Models\PortfolioItem;
use App\Models\Service;
use Illuminate\Database\Seeder;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\DataType;
use TCG\Voyager\Models\Menu;
use TCG\Voyager\Models\MenuItem;
use TCG\Voyager\Models\Permission;
use TCG\Voyager\Models\Role;

/**
 * Registers the `portfolio_items` table as a Voyager BREAD. Same approach
 * as VoyagerServicesBreadSeeder — scripted equivalent of Tools > BREAD >
 * Add New, safe to re-run.
 */
class VoyagerPortfolioBreadSeeder extends Seeder
{
    public function run(): void
    {
        $dataType = DataType::updateOrCreate(
            ['name' => 'portfolio_items'],
            [
                'slug' => 'portfolio-items',
                'display_name_singular' => 'Portfolio Item',
                'display_name_plural' => 'Portfolio',
                'icon' => 'voyager-images',
                'model_name' => PortfolioItem::class,
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
                'key' => "{$action}_portfolio_items",
                'table_name' => 'portfolio_items',
            ]);

            if ($adminRole && ! $adminRole->permissions()->where('permission_id', $permission->id)->exists()) {
                $adminRole->permissions()->attach($permission->id);
            }
        }

        $adminMenu = Menu::where('name', 'admin')->first();

        if ($adminMenu) {
            MenuItem::updateOrCreate(
                ['menu_id' => $adminMenu->id, 'route' => 'voyager.portfolio-items.index'],
                [
                    'title' => 'Portfolio',
                    'url' => '',
                    'target' => '_self',
                    'icon_class' => 'voyager-images',
                    'color' => null,
                    'parent_id' => null,
                    'order' => 8,
                    'parameters' => null,
                ]
            );
        }
    }

    private function rows(): array
    {
        return [
            ['field' => 'order', 'type' => 'number', 'display_name' => 'Order', 'required' => 0, 'browse' => 1, 'read' => 0, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => (object) []],
            ['field' => 'title', 'type' => 'text', 'display_name' => 'Title', 'required' => 1, 'browse' => 1, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => $this->required(['placeholder' => 'e.g. the book or project name'])],
            // Voyager's belongsTo-relationship convention needs TWO rows:
            // - a plain row named after the actual DB column ("service_id"),
            //   which is what really gets saved (VoyagerBaseController's
            //   insertUpdateData explicitly *skips* saving for rows where
            //   type=='relationship', on the assumption a plain row like
            //   this one exists to carry the value the <select> posted).
            // - a "relationship" row named after the Eloquent relation
            //   itself ("service", matching PortfolioItem::service()), which
            //   renders the actual dropdown. Its `field` must differ from
            //   the plain row's, or Voyager's removeRelationshipField() (hides
            //   the plain row from add/edit forms) and its relation() AJAX
            //   endpoint (looks up options by matching `field`) both become
            //   ambiguous about which of the two same-named rows they mean.
            ['field' => 'service_id', 'type' => 'hidden', 'display_name' => 'Service (internal)', 'required' => 0, 'browse' => 0, 'read' => 0, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => (object) []],
            ['field' => 'service', 'type' => 'relationship', 'display_name' => 'Service', 'required' => 1, 'browse' => 1, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => [
                'model' => Service::class,
                'table' => 'services',
                'type' => 'belongsTo',
                'column' => 'service_id',
                'key' => 'id',
                'label' => 'nav_title',
            ]],
            ['field' => 'category_label', 'type' => 'text', 'display_name' => 'Category Tag', 'required' => 1, 'browse' => 1, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => $this->required(['placeholder' => 'Short badge shown on the card, e.g. "Fiction"'])],
            ['field' => 'subtitle', 'type' => 'text', 'display_name' => 'Subtitle', 'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => ['placeholder' => 'e.g. "Literary & Contemporary"']],
            ['field' => 'image', 'type' => 'image', 'display_name' => 'Image', 'required' => 1, 'browse' => 1, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => $this->requiredOnAdd()],
            ['field' => 'image_alt', 'type' => 'text', 'display_name' => 'Image Alt Text', 'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => (object) []],
            ['field' => 'created_at', 'type' => 'timestamp', 'display_name' => 'Created At', 'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 0, 'add' => 0, 'delete' => 0, 'details' => (object) []],
            ['field' => 'updated_at', 'type' => 'timestamp', 'display_name' => 'Updated At', 'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 0, 'add' => 0, 'delete' => 0, 'details' => (object) []],
        ];
    }

    /**
     * Voyager's `required` column on a data_row is cosmetic only (just an
     * asterisk in the form) — it does NOT get enforced by the store/update
     * validator. Real enforcement needs `details.validation.rule`, which is
     * what this attaches. Without it, leaving a "required" field blank
     * sails past Voyager and crashes on the database's NOT NULL constraint
     * instead of showing a normal validation error.
     */
    private function required(array $details = []): object
    {
        return (object) array_merge($details, [
            'validation' => (object) ['rule' => ['required']],
        ]);
    }

    /**
     * Same as required(), but for an image field where forcing 'required'
     * unconditionally would also block every edit that doesn't re-upload a
     * new file (Voyager never pre-fills a file input, so the field is
     * always empty on the edit form even though the existing image is kept).
     * Required only on Add; merely validated-as-an-image on Edit.
     */
    private function requiredOnAdd(array $details = []): object
    {
        return (object) array_merge($details, [
            'validation' => (object) [
                'rule' => ['image'],
                'add' => (object) ['rule' => ['required']],
            ],
        ]);
    }
}
