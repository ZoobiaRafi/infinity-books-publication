<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Service;
use Illuminate\Database\Seeder;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\DataType;
use TCG\Voyager\Models\Menu;
use TCG\Voyager\Models\MenuItem;
use TCG\Voyager\Models\Permission;
use TCG\Voyager\Models\Role;

/**
 * Registers the `blog_posts` table as a Voyager BREAD. Same approach as
 * VoyagerPortfolioBreadSeeder — scripted equivalent of Tools > BREAD >
 * Add New, safe to re-run.
 */
class VoyagerBlogBreadSeeder extends Seeder
{
    public function run(): void
    {
        $dataType = DataType::updateOrCreate(
            ['name' => 'blog_posts'],
            [
                'slug' => 'blog-posts',
                'display_name_singular' => 'Blog Post',
                'display_name_plural' => 'Blog',
                'icon' => 'voyager-news',
                'model_name' => BlogPost::class,
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
                'key' => "{$action}_blog_posts",
                'table_name' => 'blog_posts',
            ]);

            if ($adminRole && ! $adminRole->permissions()->where('permission_id', $permission->id)->exists()) {
                $adminRole->permissions()->attach($permission->id);
            }
        }

        $adminMenu = Menu::where('name', 'admin')->first();

        if ($adminMenu) {
            MenuItem::updateOrCreate(
                ['menu_id' => $adminMenu->id, 'route' => 'voyager.blog-posts.index'],
                [
                    'title' => 'Blog',
                    'url' => '',
                    'target' => '_self',
                    'icon_class' => 'voyager-news',
                    'color' => null,
                    'parent_id' => null,
                    'order' => 9,
                    'parameters' => null,
                ]
            );
        }
    }

    private function rows(): array
    {
        return [
            ['field' => 'order', 'type' => 'number', 'display_name' => 'Order', 'required' => 0, 'browse' => 1, 'read' => 0, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => (object) []],
            ['field' => 'title', 'type' => 'text', 'display_name' => 'Title', 'required' => 1, 'browse' => 1, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => $this->required(['placeholder' => 'e.g. "Choosing the Right Ghostwriter for Your Story"'])],
            ['field' => 'slug', 'type' => 'text', 'display_name' => 'Slug', 'required' => 1, 'browse' => 1, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => ['placeholder' => 'Auto-generated from the Title — edit only if you need a different URL', 'slugify' => ['origin' => 'title']]],
            ['field' => 'category', 'type' => 'text', 'display_name' => 'Category', 'required' => 1, 'browse' => 1, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => $this->required(['placeholder' => 'e.g. "Ghostwriting", "Publishing", "Marketing"'])],
            ['field' => 'read_time', 'type' => 'text', 'display_name' => 'Read Time', 'required' => 1, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => $this->required(['placeholder' => 'e.g. "6 min read"'])],
            ['field' => 'excerpt', 'type' => 'text_area', 'display_name' => 'Excerpt', 'required' => 1, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => $this->required(['placeholder' => 'Short summary shown on the blog grid card'])],
            ['field' => 'meta_description', 'type' => 'text_area', 'display_name' => 'Meta Description', 'required' => 1, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => $this->required()],
            ['field' => 'cover', 'type' => 'image', 'display_name' => 'Cover Image', 'required' => 1, 'browse' => 1, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => $this->requiredOnAdd()],
            ['field' => 'body', 'type' => 'rich_text_box', 'display_name' => 'Body', 'required' => 1, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => $this->required()],
            ['field' => 'cta_eyebrow', 'type' => 'text', 'display_name' => 'CTA Eyebrow', 'required' => 1, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => $this->required()],
            ['field' => 'cta_heading', 'type' => 'text', 'display_name' => 'CTA Heading', 'required' => 1, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => $this->required()],
            ['field' => 'cta_text', 'type' => 'text_area', 'display_name' => 'CTA Paragraph', 'required' => 1, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => $this->required()],
            // belongsToMany needs only a single relationship-type row — unlike
            // belongsTo, there's no separate hidden column to fight over: the
            // multi-select posts its values under this row's own `field`
            // name, and Voyager syncs them into the pivot table after save.
            ['field' => 'related_services', 'type' => 'relationship', 'display_name' => 'Related Services', 'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => [
                'model' => Service::class,
                'table' => 'services',
                'type' => 'belongsToMany',
                'pivot_table' => 'blog_post_service',
                'key' => 'id',
                'label' => 'nav_title',
            ]],
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
