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
        Schema::create('m_department', function (Blueprint $table) {
            Schema::create('m_department', function (Blueprint $table) {
                $table->id(); 
                $table->string('name', 100); 
                $table->string('status', 20); 
                $table->timestamps(); 
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_department');
    }
};
