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
            ['field' => 'slug', 'type' => 'text', 'display_name' => 'Slug', 'required' => 1, 'browse' => 1, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => ['placeholder' => 'Auto-generated from the Nav / Card Title — edit only if you need a different URL', 'slugify' => ['origin' => 'nav_title']]],
            ['field' => 'nav_title', 'type' => 'text', 'display_name' => 'Nav / Card Title', 'required' => 1, 'browse' => 1, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => (object) []],
            ['field' => 'summary', 'type' => 'text_area', 'display_name' => 'Card Summary', 'required' => 1, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => ['placeholder' => 'Write a brief summary / one-liner shown on the services grid card']],
            ['field' => 'icon', 'type' => 'icon_picker', 'display_name' => 'Icon', 'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => (object) []],
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
            ['field' => 'cta_eyebrow', 'type' => 'text', 'display_name' => 'CTA Eyebrow', 'required' => 1, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => (object) []],
            ['field' => 'cta_heading', 'type' => 'text', 'display_name' => 'CTA Heading', 'required' => 1, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => (object) []],
            ['field' => 'cta_text', 'type' => 'text_area', 'display_name' => 'CTA Paragraph', 'required' => 1, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => (object) []],
            ['field' => 'faq_question_1', 'type' => 'text', 'display_name' => 'FAQ 1 — Question', 'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => ['placeholder' => 'Leave both FAQ 1 fields blank to skip this FAQ']],
            ['field' => 'faq_answer_1', 'type' => 'text_area', 'display_name' => 'FAQ 1 — Answer', 'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => (object) []],
            ['field' => 'faq_question_2', 'type' => 'text', 'display_name' => 'FAQ 2 — Question', 'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => (object) []],
            ['field' => 'faq_answer_2', 'type' => 'text_area', 'display_name' => 'FAQ 2 — Answer', 'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => (object) []],
            ['field' => 'faq_question_3', 'type' => 'text', 'display_name' => 'FAQ 3 — Question', 'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => (object) []],
            ['field' => 'faq_answer_3', 'type' => 'text_area', 'display_name' => 'FAQ 3 — Answer', 'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => (object) []],
            ['field' => 'faq_question_4', 'type' => 'text', 'display_name' => 'FAQ 4 — Question', 'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => (object) []],
            ['field' => 'faq_answer_4', 'type' => 'text_area', 'display_name' => 'FAQ 4 — Answer', 'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => (object) []],
            ['field' => 'faq_question_5', 'type' => 'text', 'display_name' => 'FAQ 5 — Question', 'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => (object) []],
            ['field' => 'faq_answer_5', 'type' => 'text_area', 'display_name' => 'FAQ 5 — Answer', 'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => (object) []],
            ['field' => 'created_at', 'type' => 'timestamp', 'display_name' => 'Created At', 'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 0, 'add' => 0, 'delete' => 0, 'details' => (object) []],
            ['field' => 'updated_at', 'type' => 'timestamp', 'display_name' => 'Updated At', 'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 0, 'add' => 0, 'delete' => 0, 'details' => (object) []],
        ];
    }
}
