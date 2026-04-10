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
        Schema::create('ung_viens', function (Blueprint $table) {
            $table->id();
            $table->string('ho_ten', 50);
            $table->string('email', 255);
            $table->string('password', 255);
            $table->string('so_dien_thoai', 20)->nullable();
            $table->text('file_cv')->nullable()->comment('Đường dẫn file CV');
            $table->tinyInteger('tinh_trang')->default(1);
            $table->text('ghi_chu')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ung_viens');
    }
};
