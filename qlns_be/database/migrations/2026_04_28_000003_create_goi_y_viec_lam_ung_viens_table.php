<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('goi_y_viec_lam_ung_viens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ung_vien_id')->constrained('ung_viens')->cascadeOnDelete();
            $table->foreignId('vi_tri_tuyen_dung_id')->constrained('vi_tri_tuyen_dungs')->cascadeOnDelete();
            $table->foreignId('danh_gia_cv_ung_tuyen_id')->nullable()->constrained('danh_gia_cv_ung_tuyens')->nullOnDelete();
            $table->integer('diem_phu_hop')->default(0);
            $table->text('ly_do')->nullable();
            $table->json('ky_nang_phu_hop')->nullable();
            $table->string('trang_thai')->default('active');
            $table->timestamps();

            $table->unique(['ung_vien_id', 'vi_tri_tuyen_dung_id']);
            $table->index(['ung_vien_id', 'diem_phu_hop']);
            $table->index('trang_thai');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('goi_y_viec_lam_ung_viens');
    }
};
