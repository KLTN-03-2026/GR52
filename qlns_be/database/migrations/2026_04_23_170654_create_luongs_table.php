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
        Schema::create('luongs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_nhan_vien');
            $table->tinyInteger('thang');
            $table->smallInteger('nam');

            // ── INPUT ──
            $table->decimal('luong_co_ban', 15, 0);
            $table->integer('so_ngay_lam_viec_chuan')->default(26)->comment('Ngày công chuẩn tháng');
            $table->decimal('so_gio_lam_thuc_te', 8, 2)->default(0);
            $table->decimal('gio_chuan_ngay', 4, 1)->default(8);

            // ── NGHỈ PHÉP ──
            $table->integer('so_ngay_nghi_co_phep')->default(0)->comment('Phép năm + ốm = không trừ');
            $table->integer('so_ngay_nghi_khong_luong')->default(0)->comment('Trừ lương');

            // ── KPI ──
            $table->decimal('tong_diem_kpi', 8, 2)->default(0);
            $table->string('xep_loai_kpi', 2)->nullable();
            $table->decimal('he_so_kpi', 5, 3)->default(1.0)->comment('A=1.2, B=1.0, C=0.8, D=0.5');

            // ── THƯỞNG PHẠT ──
            $table->decimal('tong_thuong', 15, 0)->default(0);
            $table->decimal('tong_phat',   15, 0)->default(0);

            // ── TÍNH TOÁN ──
            $table->decimal('luong_ngay',         15, 2)->default(0)->comment('LCB / so_ngay_chuan');
            $table->decimal('luong_thuc_te',      15, 0)->default(0)->comment('Lương theo số ngày thực làm');
            $table->decimal('thuong_kpi',         15, 0)->default(0)->comment('LCB * (he_so_kpi - 1)');
            $table->decimal('khau_tru_nghi',      15, 0)->default(0)->comment('Trừ ngày nghỉ không lương');
            $table->decimal('luong_truoc_thue',   15, 0)->default(0);
            $table->decimal('thue_tncn',          15, 0)->default(0)->comment('Tạm thời = 0, mở rộng sau');
            $table->decimal('luong_thuc_nhan',    15, 0)->default(0);

            $table->enum('trang_thai', ['nhap', 'da_duyet', 'da_tra'])->default('nhap');
            $table->text('ghi_chu')->nullable();
            $table->timestamps();

            $table->foreign('id_nhan_vien')->references('id')->on('nhan_viens')->cascadeOnDelete();
            $table->unique(['id_nhan_vien', 'thang', 'nam']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('luongs');
    }
};
