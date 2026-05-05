<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (User::count() > 0) {
            $this->command->info('Data already exists, skipping seed.');
            return;
        }

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
