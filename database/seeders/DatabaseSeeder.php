<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {

        $this->call([
            AccountAdminSeeder::class,
            GlobalTitleSeeder::class,
            MInventorySeeder::class,
            MWarehouseSeeder::class,
            PermissionsTableSeeder::class,
            RolesTableSeeder::class,
            SidebarItemsTableSeeder::class,
            RolesAndPermissionsSeeder::class,
            TAjuSeeder::class,
        ]);
    }
}
