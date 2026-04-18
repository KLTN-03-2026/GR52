<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nhan_viens', function (Blueprint $table) {
            // role: 'admin' = quản trị hệ thống, 'hr' = nhân viên HR, 'staff' = nhân viên thường
            $table->string('role', 20)->default('staff')->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('nhan_viens', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
