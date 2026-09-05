<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\DataType;
use TCG\Voyager\Models\Menu;
use TCG\Voyager\Models\MenuItem;
use TCG\Voyager\Models\Permission;
use TCG\Voyager\Models\Role;

/**
 * Registers the `services` table as a Voyager BREAD, equivalent to what
 * Tools > BREAD > Add New would produce through the admin UI, but scripted
 * so it can be applied on any environment with one seeder run. Safe to
 * re-run — every row is upserted, not duplicated.
 */
class VoyagerServicesBreadSeeder extends Seeder
{
    public function run(): void
    {
        $dataType = DataType::updateOrCreate(
            ['name' => 'services'],
            [
                'slug' => 'services',
                'display_name_singular' => 'Service',
                'display_name_plural' => 'Services',
                'icon' => 'voyager-book',
                'model_name' => Service::class,
                'policy_name' => null,
                'controller' => null,
                'description' => '',
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => (object) [],
            ]
        );

        foreach ($this->rows() as $order => $row) {
            DataRow::updateOrCreate(
                ['data_type_id' => $dataType->id, 'field' => $row['field']],
                array_merge($row, ['data_type_id' => $dataType->id, 'order' => $order + 1])
            );
        }

        $adminRole = Role::where('name', 'admin')->first();

        foreach (['browse', 'read', 'edit', 'add', 'delete'] as $action) {
            $permission = Permission::updateOrCreate([
                'key' => "{$action}_services",
                'table_name' => 'services',
            ]);

            if ($adminRole && ! $adminRole->permissions()->where('permission_id', $permission->id)->exists()) {
                $adminRole->permissions()->attach($permission->id);
            }
        }

        $adminMenu = Menu::where('name', 'admin')->first();

        if ($adminMenu) {
            MenuItem::updateOrCreate(
                ['menu_id' => $adminMenu->id, 'route' => 'voyager.services.index'],
                [
                    'title' => 'Services',
                    'url' => '',
                    'target' => '_self',
                    'icon_class' => 'voyager-book',
                    'color' => null,
                    'parent_id' => null,
                    'order' => 7,
                    'parameters' => null,
                ]
            );
        }
    }

    private function rows(): array
    {
        return [
            ['field' => 'order', 'type' => 'number', 'display_name' => 'Order', 'required' => 0, 'browse' => 1, 'read' => 0, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => (object) []],
            ['field' => 'slug', 'type' => 'text', 'display_name' => 'Slug', 'required' => 1, 'browse' => 1, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => ['placeholder' => 'used in the URL, e.g. book-formatting — lowercase, hyphens only']],
            ['field' => 'nav_title', 'type' => 'text', 'display_name' => 'Nav / Card Title', 'required' => 1, 'browse' => 1, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => (object) []],
            ['field' => 'summary', 'type' => 'text_area', 'display_name' => 'Card Summary', 'required' => 1, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => ['placeholder' => 'One or two sentences shown on the services grid card']],
            ['field' => 'icon', 'type' => 'text_area', 'display_name' => 'Icon (SVG markup)', 'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => ['placeholder' => 'Paste a full <svg>...</svg> tag, e.g. from lucide.dev']],
            ['field' => 'title', 'type' => 'text', 'display_name' => 'Page Title', 'required' => 1, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => ['placeholder' => 'e.g. "Ghostwriting That Sounds Like "']],
            ['field' => 'title_accent', 'type' => 'text', 'display_name' => 'Page Title (accent word)', 'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => ['placeholder' => 'e.g. "You" — highlighted in orange after the title']],
            ['field' => 'meta_description', 'type' => 'text_area', 'display_name' => 'Meta Description', 'required' => 1, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => (object) []],
            ['field' => 'lede', 'type' => 'text_area', 'display_name' => 'Hero Paragraph', 'required' => 1, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => (object) []],
            ['field' => 'included_heading', 'type' => 'text', 'display_name' => '"What\'s Included" Heading', 'required' => 1, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => (object) []],
            ['field' => 'included_text', 'type' => 'text_area', 'display_name' => '"What\'s Included" Paragraph', 'required' => 1, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => (object) []],
            ['field' => 'included_list', 'type' => 'text_area', 'display_name' => 'Included Checklist', 'required' => 1, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => ['placeholder' => 'One checklist item per line']],
            ['field' => 'image', 'type' => 'image', 'display_name' => 'Image', 'required' => 0, 'browse' => 1, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => (object) []],
            ['field' => 'image_alt', 'type' => 'text', 'display_name' => 'Image Alt Text', 'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => (object) []],
            ['field' => 'process_heading', 'type' => 'text', 'display_name' => '"How It Works" Heading', 'required' => 1, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => (object) []],
            ['field' => 'steps', 'type' => 'text_area', 'display_name' => 'Process Steps', 'required' => 1, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => ['placeholder' => "One step per line, formatted as: Step Title|Step description text"]],
            ['field' => 'testimonial_title', 'type' => 'text', 'display_name' => 'Testimonial Title', 'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => (object) []],
            ['field' => 'testimonial_quote', 'type' => 'text_area', 'display_name' => 'Testimonial Quote', 'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => ['placeholder' => 'Leave blank to hide the testimonial section entirely']],
            ['field' => 'testimonial_initials', 'type' => 'text', 'display_name' => 'Testimonial Initials', 'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => (object) []],
            ['field' => 'testimonial_name', 'type' => 'text', 'display_name' => 'Testimonial Name', 'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => (object) []],
            ['field' => 'testimonial_date', 'type' => 'text', 'display_name' => 'Testimonial Date', 'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => (object) []],
            ['field' => 'cta_eyebrow', 'type' => 'text', 'display_name' => 'CTA Eyebrow', 'required' => 1, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => (object) []],
            ['field' => 'cta_heading', 'type' => 'text', 'display_name' => 'CTA Heading', 'required' => 1, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => (object) []],
            ['field' => 'cta_text', 'type' => 'text_area', 'display_name' => 'CTA Paragraph', 'required' => 1, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => (object) []],
            ['field' => 'created_at', 'type' => 'timestamp', 'display_name' => 'Created At', 'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 0, 'add' => 0, 'delete' => 0, 'details' => (object) []],
            ['field' => 'updated_at', 'type' => 'timestamp', 'display_name' => 'Updated At', 'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 0, 'add' => 0, 'delete' => 0, 'details' => (object) []],
        ];
    }
}
