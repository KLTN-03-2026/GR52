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
        Schema::create('danh_gia_cv_ung_tuyens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ho_so_ung_tuyen_id')->constrained('ho_so_ung_tuyens')->onDelete('cascade');
            $table->foreignId('vi_tri_tuyen_dung_id')->constrained('vi_tri_tuyen_dungs')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('nhan_viens')->onDelete('cascade'); // HR who analyzed

            // Scoring
            $table->integer('tong_diem')->default(0); // 0-100 score
            $table->string('khuyến_nghị')->nullable(); // Recommendation: Phù hợp, Cần xem xét, Không phù hợp

            // Matched & Missing Skills (JSON)
            $table->json('kỹ_năng_phù_hợp')->nullable(); // Matched skills array
            $table->json('kỹ_năng_thiếu')->nullable(); // Missing skills array

            // Analysis
            $table->json('điểm_mạnh')->nullable(); // Strengths array
            $table->json('điểm_yếu')->nullable(); // Weaknesses array
            $table->longText('tóm_tắt_phân_tích')->nullable(); // Summary

            // Raw AI Response (for debugging)
            $table->longText('ai_response')->nullable();

            // Tracking
            $table->string('trạng_thái')->default('completed'); // completed, error, pending
            $table->string('lỗi')->nullable(); // Error message if any

            $table->timestamps();
            $table->softDeletes();

            // Indexes for fast queries
            $table->index('ho_so_ung_tuyen_id');
            $table->index('vi_tri_tuyen_dung_id');
            $table->index('tong_diem');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('danh_gia_cv_ung_tuyens');
    }
};
