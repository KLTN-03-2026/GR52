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
        Schema::create('loai_hop_dongs', function (Blueprint $table) {
            $table->id();
            $table->string('ten_hop_dong', 100);
            $table->longText('noi_dung')->nullable();
            $table->tinyInteger('tinh_trang')->default(1)->comment('1: Đang áp dụng, 0: Ngưng');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loai_hop_dongs');
    }
};
