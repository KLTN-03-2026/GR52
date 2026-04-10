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
        Schema::create('tieu_chi_danh_gia_tds', function (Blueprint $table) {
            $table->id();
            $table->string('tieu_de', 255);
            $table->string('mo_ta', 500)->nullable();
            $table->integer('diem_toi_da')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tieu_chi_danh_gia_tds');
    }
};
