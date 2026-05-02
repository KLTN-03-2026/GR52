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
        Schema::create('thuong_va_phats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_nhan_vien');
            $table->tinyInteger('thang');
            $table->smallInteger('nam');
            $table->enum('loai', ['thuong', 'phat']);
            $table->decimal('so_tien', 15, 0)->comment('VNĐ, luôn dương — loai quyết định +/-');
            $table->string('ly_do', 500)->nullable();
            $table->unsignedBigInteger('id_nguoi_tao')->nullable();
            $table->timestamps();
            $table->foreign('id_nhan_vien')->references('id')->on('nhan_viens')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thuong_va_phats');
    }
};
