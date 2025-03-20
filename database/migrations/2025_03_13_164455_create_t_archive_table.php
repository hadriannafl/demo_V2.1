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
        Schema::create('t_archive', function (Blueprint $table) {
            $table->id('idrec');
            $table->date('date')->nullable();
            $table->string('no_archive', 100)->nullable();
            $table->unsignedBigInteger('id_aju')->nullable();
            $table->binary('pdf_jpg')->nullable();
            $table->string('file_name', 255)->nullable();
            $table->char('active_y_n', 1)->default('Y');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('id_aju')->references('id_aju')->on('t_aju')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_archive');
    }
};
