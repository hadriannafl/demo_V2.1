<?php

namespace Database\Seeders;

use App\Models\Aju;
use Illuminate\Database\Seeder;

class AjuSeeder extends Seeder
{
    /**
     * Jalankan seeder.
     */
    public function run(): void
    {
        Aju::insert([
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
