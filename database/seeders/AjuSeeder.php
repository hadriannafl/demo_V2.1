<?php

namespace Database\Seeders;

use App\Models\Aju;
use Illuminate\Database\Seeder;

class AjuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Aju::insert([
            [
                'date' => now(),
                'no_aju' => 'AJU-20240101',
                'id_archive' => 1,
                'active_y_n' => 'Y',
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'date' => now(),
                'no_aju' => 'AJU-20240102',
                'id_archive' => 2,
                'active_y_n' => 'Y',
                'created_by' => 1,
                'updated_by' => 1
            ],
        ]);
    }
}
