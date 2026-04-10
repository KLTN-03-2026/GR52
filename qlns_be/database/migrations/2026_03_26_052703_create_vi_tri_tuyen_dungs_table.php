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
        Schema::create('vi_tri_tuyen_dungs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_phong_ban')->nullable();
            $table->unsignedBigInteger('id_chuc_vu')->nullable();
            $table->string('tieu_de', 100)->nullable();
            $table->longText('mo_ta')->nullable();
            $table->integer('so_luong')->nullable();
            $table->dateTime('ngay_bat_dau')->nullable();
            $table->dateTime('ngay_ket_thuc')->nullable();
            $table->tinyInteger('tinh_trang')->default(1)
                  ->comment('1: Đang tuyển, 0: Đã đóng');
            $table->timestamps();

           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vi_tri_tuyen_dungs');
    }
};
