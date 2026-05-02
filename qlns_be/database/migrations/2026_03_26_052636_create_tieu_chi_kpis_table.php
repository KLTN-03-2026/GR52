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
        Schema::create('tieu_chi_kpis', function (Blueprint $table) {
            $table->id();
            $table->string('ten_tieu_chi', 200);
            $table->text('mo_ta')->nullable();
            $table->decimal('trong_so', 5, 2)->default(100)->comment('% trọng số, tổng = 100');
            $table->decimal('muc_tieu', 10, 2)->comment('Giá trị mục tiêu cần đạt');
            $table->string('don_vi', 50)->nullable()->comment('%, số lượng, giờ...');
            $table->tinyInteger('tinh_trang')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tieu_chi_kpis');
    }
};
