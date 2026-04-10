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
        Schema::create('danh_gia_ung_viens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_ho_so_ung_tuyen');
            $table->unsignedBigInteger('id_tieu_chi');
            $table->integer('diem_danh_gia');
            $table->unsignedBigInteger('id_nhan_vien_danh_gia');
            $table->timestamps();

    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('danh_gia_ung_viens');
    }
};
