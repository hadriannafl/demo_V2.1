<?php

namespace Database\Seeders;

use App\Models\Archive;
use Illuminate\Database\Seeder;

class ArchiveSeeder extends Seeder
{
    /**
     * Jalankan seeder.
     */
    public function run(): void
    {
        Archive::insert([
            [
                'date' => now(),
                'no_archive' => 'ARC-20240101',
                'id_aju' => 1, // Sesuai dengan Aju yang sudah dibuat
                'pdf_jpg' => null,
                'file_name' => 'invoice_archive.pdf',
                'active_y_n' => 'Y',
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'date' => now(),
                'no_archive' => 'ARC-20240102',
                'id_aju' => 2, // Sesuai dengan Aju yang sudah dibuat
                'pdf_jpg' => null,
                'file_name' => 'contract_archive.pdf',
                'active_y_n' => 'Y',
                'created_by' => 1,
                'updated_by' => 1
            ],
        ]);
    }
}
