<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chi_tiet_hop_dongs', function (Blueprint $table) {
            $table->string('trang_thai')->default('nhap')->after('ngay_ket_thuc');
            $table->string('chu_ky_admin')->nullable()->after('trang_thai');
            $table->timestamp('ngay_admin_ky')->nullable()->after('chu_ky_admin');
            $table->string('chu_ky_nhan_vien')->nullable()->after('ngay_admin_ky');
            $table->timestamp('ngay_nhan_vien_ky')->nullable()->after('chu_ky_nhan_vien');
            $table->string('file_pdf')->nullable()->after('ngay_nhan_vien_ky');
        });
    }

    public function down(): void
    {
        Schema::table('chi_tiet_hop_dongs', function (Blueprint $table) {
            $table->dropColumn([
                'trang_thai',
                'chu_ky_admin',
                'ngay_admin_ky',
                'chu_ky_nhan_vien',
                'ngay_nhan_vien_ky',
                'file_pdf',
            ]);
        });
    }
};
