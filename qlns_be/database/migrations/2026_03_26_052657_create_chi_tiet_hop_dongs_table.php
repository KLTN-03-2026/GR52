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
        Schema::create('chi_tiet_hop_dongs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_nhan_vien');
            $table->unsignedBigInteger('id_loai_hop_dong');
            $table->longText('noi_dung')->nullable();
            $table->date('ngay_ky');
            $table->date('ngay_bat_dau');
            $table->date('ngay_ket_thuc')->nullable()->comment('NULL = không thời hạn');
            $table->timestamps();

          
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chi_tiet_hop_dongs');
    }
};
