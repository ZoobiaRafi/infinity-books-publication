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
            ['field' => 'title', 'type' => 'text', 'display_name' => 'Title', 'required' => 1, 'browse' => 1, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => ['placeholder' => 'e.g. "Choosing the Right Ghostwriter for Your Story"']],
            ['field' => 'slug', 'type' => 'text', 'display_name' => 'Slug', 'required' => 1, 'browse' => 1, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => ['placeholder' => 'Auto-generated from the Title — edit only if you need a different URL', 'slugify' => ['origin' => 'title']]],
            ['field' => 'category', 'type' => 'text', 'display_name' => 'Category', 'required' => 1, 'browse' => 1, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => ['placeholder' => 'e.g. "Ghostwriting", "Publishing", "Marketing"']],
            ['field' => 'read_time', 'type' => 'text', 'display_name' => 'Read Time', 'required' => 1, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => ['placeholder' => 'e.g. "6 min read"']],
            ['field' => 'excerpt', 'type' => 'text_area', 'display_name' => 'Excerpt', 'required' => 1, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => ['placeholder' => 'Short summary shown on the blog grid card']],
            ['field' => 'meta_description', 'type' => 'text_area', 'display_name' => 'Meta Description', 'required' => 1, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => (object) []],
            ['field' => 'cover', 'type' => 'image', 'display_name' => 'Cover Image', 'required' => 1, 'browse' => 1, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => (object) []],
            ['field' => 'body', 'type' => 'rich_text_box', 'display_name' => 'Body', 'required' => 1, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => (object) []],
            ['field' => 'cta_eyebrow', 'type' => 'text', 'display_name' => 'CTA Eyebrow', 'required' => 1, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => (object) []],
            ['field' => 'cta_heading', 'type' => 'text', 'display_name' => 'CTA Heading', 'required' => 1, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => (object) []],
            ['field' => 'cta_text', 'type' => 'text_area', 'display_name' => 'CTA Paragraph', 'required' => 1, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => (object) []],
            // Same two-row belongsTo convention as VoyagerPortfolioBreadSeeder —
            // see the comment there for why the hidden column row and the
            // relationship row need different `field` names.
            ['field' => 'cta_service_id', 'type' => 'hidden', 'display_name' => 'CTA Service (internal)', 'required' => 0, 'browse' => 0, 'read' => 0, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => (object) []],
            ['field' => 'cta_service', 'type' => 'relationship', 'display_name' => 'CTA Service', 'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => [
                'model' => Service::class,
                'table' => 'services',
                'type' => 'belongsTo',
                'column' => 'cta_service_id',
                'key' => 'id',
                'label' => 'nav_title',
            ]],
            ['field' => 'cta_button_text', 'type' => 'text', 'display_name' => 'CTA Button Text', 'required' => 1, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'details' => ['placeholder' => 'e.g. "Explore Ghostwriting Services"']],
            ['field' => 'created_at', 'type' => 'timestamp', 'display_name' => 'Created At', 'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 0, 'add' => 0, 'delete' => 0, 'details' => (object) []],
            ['field' => 'updated_at', 'type' => 'timestamp', 'display_name' => 'Updated At', 'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 0, 'add' => 0, 'delete' => 0, 'details' => (object) []],
        ];
    }
}
