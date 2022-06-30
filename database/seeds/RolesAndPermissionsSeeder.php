<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $collection = collect([
            'users',
            'roles',
            'permissions',
            'banners',
            'diamonds',
            'articles',
            'article_detail_blocks',
            'blog_categories',
            'cart_items',
            'clarities',
            'colors',
            'compares',
            'complete_rings',
            'contact_links',
            'country_vats',
            'culets',
            'currency_rates',
            'cuts',
            'dynamic_pages',
            'engagement_rings',
            'engagement_ring_styles',
            'faqs',
            'filter_descriptions',
            'fluorescences',
            'locations',
            'main_sliders',
            'main_slider_main_slider_slide',
            'main_slider_slides',
            'main_slider_videos',
            'manufacturers',
            'margins',
            'menu_dropdown_contents',
            'metals',
            'offers',
            'order',
            'paysystems',
            'polishes',
            'product_categories',
            'product_hints',
            'reviews',
            'ring_collections',
            'ring_sizes',
            'seo_blocks',
            'seo_meta',
            'shapes',
            'show_room_countries',
            'show_rooms',
            'static_blocks',
            'static_blocks_diamonds',
            'static_blocks_engagement_rings',
            'static_blocks_wedding_rings',
            'static_pages',
            'status',
            'subscribes',
            'symmetries',
            'wedding_rings',
            'wedding_ring_styles',
        ]);

        $collection->each(function ($item, $key) {
            // create permissions for each collection item
            Permission::create(['group' => $item, 'name' => 'view ' . $item]);
            Permission::create(['group' => $item, 'name' => 'view own ' . $item]);
            Permission::create(['group' => $item, 'name' => 'manage ' . $item]);
            Permission::create(['group' => $item, 'name' => 'manage own ' . $item]);
            Permission::create(['group' => $item, 'name' => 'restore ' . $item]);
            Permission::create(['group' => $item, 'name' => 'forceDelete ' . $item]);
        });

        // Create a Super-Admin Role and assign all Permissions
        $role = Role::create(['name' => 'super-admin']);
        $role_admin = Role::create(['name' => 'admin']);
        $role_content = Role::create(['name' => 'content-manager']);
        $role_sale = Role::create(['name' => 'sales-manager']);
        $role->givePermissionTo(Permission::all());
        $role_admin->givePermissionTo(Permission::whereNotIn('group', ['roles', 'permissions'])->get());
        $role_content->givePermissionTo(Permission::whereNotIn('group',
            ['roles', 'permissions', 'orders', 'margin', 'users', 'cart_items', 'status', 'paysystems', 'order'])
            ->get());
        $not_dell = [];
        foreach ($collection->toArray() as $item) {
            $not_dell[] = 'forceDelete ' . $item;
            $not_dell[] = 'manage ' . $item;
            $not_dell[] = 'manage own ' . $item;
            $not_dell[] = 'restore ' . $item;
        }
        $role_sale->givePermissionTo(Permission::whereNotIn('group', ['roles', 'permissions', 'margin', 'users'])
            ->whereNotIn('name', $not_dell)
            ->get());
        $perm_update = ['order', 'cart_items', 'status', 'paysystems'];
        foreach ($perm_update as $item) {
            $status_update[] = 'manage ' . $item;
        }
        $role_sale->givePermissionTo(Permission::whereIn('group', $perm_update)
            ->whereIn('name', $status_update)
            ->get());

        // Give User Super-Admin Role
        $user = App\User::whereEmail('z.mustafaieva@lenal.biz')->first();
        $user->assignRole('super-admin');
        $user_mark = App\User::whereEmail('marketing@palati.agency ')->first();
        $user_mark->assignRole('super-admin');

        // Give User Admin Role
        $user_admin = App\User::whereEmail('z.mustafaieva@gmail.com')->first();
        $user_admin->assignRole('admin');

        // Give User Sale Role
        $sale = App\User::whereEmail('zineb.mustafaieva@gmail.com')->first();
        $sale->assignRole('sales-manager');

        // Give User Manager Role
        $manager = App\User::whereEmail('zineb@mustafaieva.com')->first();
        $manager->assignRole('content-manager');
    }
}
