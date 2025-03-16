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
            $table->id('idrec');
            $table->date('date');
            $table->string('no_aju', 100);
            $table->unsignedBigInteger('id_archive');
            $table->binary('pdf_jpg')->nullable();
            $table->char('active_y_n', 1)->default('Y');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('id_archive')->references('id_archive')->on('t_archive')->onDelete('cascade');
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
