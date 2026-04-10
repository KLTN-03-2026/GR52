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
        Schema::create('tieu_chi_kpis', function (Blueprint $table) {
            $table->id();
            $table->string('ten_tieu_chi', 255)->nullable();
            $table->string('mo_ta', 255)->nullable();
            $table->double('diem')->nullable();
            $table->string('tinh_trang', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tieu_chi_kpis');
    }
};
