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
        Schema::create('cham_congs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_nhan_vien');
            $table->date('ngay_lam_viec');
            $table->string('ca_lam', 20)->comment('sang/chieu/toi');
            $table->time('gio_vao')->nullable();
            $table->time('gio_ra')->nullable();
            $table->decimal('so_gio_lam', 5, 2)->nullable();
            $table->string('anh_check_in', 500)->nullable()->comment('Đường dẫn ảnh selfie khi check-in');
            $table->string('anh_check_out', 500)->nullable()->comment('Đường dẫn ảnh selfie khi check-out');
            $table->tinyInteger('trang_thai')->default(0)
                  ->comment('0: Chưa ra, 1: Đã check-out, 2: Admin xác nhận, 3: Nghi ngờ');
            $table->text('ghi_chu_nhan_vien')->nullable();
            $table->text('ghi_chu_admin')->nullable();
            $table->timestamps();

            $table->foreign('id_nhan_vien')->references('id')->on('nhan_viens')->cascadeOnDelete();
            $table->unique(['id_nhan_vien', 'ngay_lam_viec', 'ca_lam'], 'uq_nv_ngay_ca');
        });
    }

    public function down(): void { Schema::dropIfExists('cham_congs'); }
};
