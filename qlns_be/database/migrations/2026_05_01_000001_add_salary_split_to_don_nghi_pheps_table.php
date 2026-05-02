<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('don_nghi_pheps', function (Blueprint $table) {
            $table->integer('so_ngay_co_luong')->nullable()->after('so_ngay');
            $table->integer('so_ngay_khong_luong')->nullable()->after('so_ngay_co_luong');
            $table->text('ghi_chu_duyet')->nullable()->after('ly_do_tu_choi');
        });
    }

    public function down(): void
    {
        Schema::table('don_nghi_pheps', function (Blueprint $table) {
            $table->dropColumn(['so_ngay_co_luong', 'so_ngay_khong_luong', 'ghi_chu_duyet']);
        });
    }
};
