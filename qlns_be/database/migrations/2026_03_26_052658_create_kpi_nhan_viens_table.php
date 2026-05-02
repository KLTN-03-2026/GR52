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
        Schema::create('kpi_nhan_viens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_nhan_vien');
            $table->unsignedBigInteger('id_tieu_chi');
            $table->tinyInteger('thang');
            $table->smallInteger('nam');
            $table->decimal('muc_tieu', 10, 2)->comment('Mục tiêu tháng này (copy từ template, có thể override)');
            $table->decimal('ket_qua_thuc_te', 10, 2)->nullable()->comment('Kết quả nhập vào cuối tháng');
            $table->decimal('phan_tram_hoan_thanh', 6, 2)->nullable()->comment('Tự tính: ket_qua / muc_tieu * 100');
            $table->decimal('diem_kpi', 6, 2)->nullable()->comment('trong_so * phan_tram_hoan_thanh / 100');
            $table->string('xep_loai', 2)->nullable()->comment('A/B/C/D');
            $table->text('ghi_chu')->nullable();
            $table->timestamps();

            $table->foreign('id_nhan_vien')->references('id')->on('nhan_viens')->cascadeOnDelete();
            $table->foreign('id_tieu_chi')->references('id')->on('tieu_chi_kpis')->cascadeOnDelete();
            $table->unique(['id_nhan_vien', 'id_tieu_chi', 'thang', 'nam']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kpi_nhan_viens');
    }
};
