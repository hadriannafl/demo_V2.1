<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('t_aju', function (Blueprint $table) {
            $table->id('id_aju');
            $table->date('date');
            $table->unsignedBigInteger('id_department');
            $table->string('tipe_docs', 50);
            $table->string('no_docs', 100);
            $table->text('description')->nullable();
            $table->binary('pdf_jpg')->nullable();
            $table->string('file_name', 255)->nullable();
            $table->char('active_y_n', 1)->default('Y');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_aju');
    }
};
