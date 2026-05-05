<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AccountAdminSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            ['name' => 'Admin',          'email' => 'admin_tsno@gmail.com',      'role_id' => 1],
            ['name' => 'User',           'email' => 'user_tsno@gmail.com',       'role_id' => 2],
            ['name' => 'Purchasing User','email' => 'purchasing_tsno@gmail.com', 'role_id' => 3],
        ];

        foreach ($accounts as $account) {
            User::firstOrCreate(
                ['email' => $account['email']],
                ['name' => $account['name'], 'password' => bcrypt('user123'), 'role_id' => $account['role_id']]
            );
        }
    }
}
