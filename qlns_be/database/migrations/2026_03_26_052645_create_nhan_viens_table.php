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
        Schema::create('nhan_viens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_phong_ban')->nullable();
            $table->unsignedBigInteger('id_chuc_vu')->nullable();
            $table->string('ho_va_ten', 100);
            $table->string('email', 100)->unique();
            $table->string('password', 255);
            $table->date('ngay_sinh')->nullable();
            $table->text('dia_chi')->nullable();
            $table->string('so_dien_thoai', 15)->nullable();
            $table->decimal('luong_co_ban', 15, 2)->default(0);
            $table->timestamps();

          $table->foreign('id_phong_ban')
                  ->references('id')->on('phong_bans')
                  ->nullOnDelete();
            $table->foreign('id_chuc_vu')
                  ->references('id')->on('chuc_vus')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nhan_viens');
    }
};
