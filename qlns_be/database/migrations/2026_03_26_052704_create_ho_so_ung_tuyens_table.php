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
        Schema::create('ho_so_ung_tuyens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ung_vien_id')->constrained('ung_viens')->onDelete('cascade');
            $table->foreignId('vi_tri_tuyen_dung_id')->constrained('vi_tri_tuyen_dungs')->onDelete('cascade');
            $table->string('file_cv')->nullable(); // Path to the CV file for this specific application
            $table->text('ghi_chu_ung_vien')->nullable(); // Candidate's cover letter/notes
            $table->timestamp('ngay_ung_tuyen')->useCurrent();
            $table->integer('trang_thai')->default(0); // 0: Đang chờ, 1: Đã xem, 2: Đã duyệt, 3: Từ chối
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ho_so_ung_tuyens');
    }
};
