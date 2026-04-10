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
        Schema::create('quy_dinh_cho_diems', function (Blueprint $table) {
            $table->id();
            $table->integer('ma_so')->nullable();
            $table->string('noi_dung', 255)->nullable();
            $table->double('so_diem');
            $table->string('loai_diem', 50)->nullable()->comment('thuong / phat');
            $table->string('tinh_trang', 50)->nullable();
            $table->string('ghi_chu', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quy_dinh_cho_diems');
    }
};
