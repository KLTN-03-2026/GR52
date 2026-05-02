<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('danh_gia_cv_ung_tuyens')) {
            return;
        }

        Schema::table('danh_gia_cv_ung_tuyens', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->foreign('created_by')->references('id')->on('nhan_viens')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('danh_gia_cv_ung_tuyens')) {
            return;
        }

        Schema::table('danh_gia_cv_ung_tuyens', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete();
        });
    }
};
