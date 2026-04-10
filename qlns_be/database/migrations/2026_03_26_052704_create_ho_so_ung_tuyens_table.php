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
        Schema::create('ho_so_ung_tuyens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_ung_vien');
            $table->unsignedBigInteger('id_chuc_vu')->nullable();
            $table->dateTime('ngay_ung_tuyen');
            $table->tinyInteger('trang_thai')->default(1);
            $table->string('trang_thai_cu', 50)->nullable()
                  ->comment('Trạng thái trước (đã gộp từ lich_su_trang_thais)');
            $table->dateTime('ngay_cap_nhat')->nullable();
            $table->string('ghi_chu', 500)->nullable();
            $table->timestamps();

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ho_so_ung_tuyens');
    }
};
