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
        Schema::create('don_nghi_pheps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_nhan_vien');
            $table->string('loai_nghi', 50)->comment('phep_nam/om/khong_luong/viec_rieng');
            $table->date('ngay_bat_dau');
            $table->date('ngay_ket_thuc');
            $table->integer('so_ngay')->default(1);
            $table->text('ly_do')->nullable();
            $table->string('nguoi_thay_the', 100)->nullable()->after('ly_do');
            $table->tinyInteger('trang_thai')->default(1)
                  ->comment('1: Chờ duyệt, 2: Đã duyệt, 3: Từ chối');
            $table->unsignedBigInteger('id_nguoi_duyet')->nullable();
            $table->dateTime('ngay_duyet')->nullable();
            $table->text('ly_do_tu_choi')->nullable();
            $table->timestamps();

            $table->foreign('id_nhan_vien')
                  ->references('id')->on('nhan_viens')
                  ->cascadeOnDelete();
            $table->foreign('id_nguoi_duyet')
                  ->references('id')->on('nhan_viens')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('don_nghi_pheps');
    }
};
