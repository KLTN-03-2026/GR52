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
            $table->unsignedBigInteger('id_nhan_vien_cho_diem');
            $table->unsignedBigInteger('id_quy_dinh');
            $table->double('diem');
            $table->string('ly_do', 255)->nullable();
            $table->dateTime('ngay')->nullable();
            $table->timestamps();


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
