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
        Schema::create('kpi_nhan_viens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_nhan_vien');
            $table->unsignedBigInteger('id_tieu_chi');
            $table->double('diem_duoc_cham');
            $table->unsignedBigInteger('id_nhan_vien_danh_gia');
            $table->dateTime('ngay_danh_gia')->nullable();
            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kpi_nhan_viens');
    }
};
