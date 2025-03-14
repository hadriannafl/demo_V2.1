<?php

namespace Database\Seeders;

use App\Models\Archive;
use Illuminate\Database\Seeder;

class ArchiveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Archive::insert([
            [
                'date' => now(),
                'id_department' => 1,
                'tipe_docs' => 'Invoice',
                'no_docs' => 'INV-20240101',
                'description' => 'Invoice for January',
                'pdf_jpg' => null,
                'file_name' => 'invoice_jan.pdf',
                'active_y_n' => 'Y',
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'date' => now(),
                'id_department' => 2,
                'tipe_docs' => 'Contract',
                'no_docs' => 'CNT-20240102',
                'description' => 'Contract agreement',
                'pdf_jpg' => null,
                'file_name' => 'contract.pdf',
                'active_y_n' => 'Y',
                'created_by' => 1,
                'updated_by' => 1
            ],
        ]);
    }
}
