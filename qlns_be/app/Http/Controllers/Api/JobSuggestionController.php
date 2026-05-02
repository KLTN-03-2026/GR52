<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DanhGiaCVUngTuyen;
use App\Models\GoiYViecLamUngVien;
use App\Services\CVAnalysisService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobSuggestionController extends Controller
{
    public function index()
    {
        $ungVien = Auth::user();

        if (!$ungVien || !($ungVien instanceof \App\Models\UngVien)) {
            return response()->json([
                'status' => false,
                'message' => 'Bạn cần đăng nhập ứng viên.',
            ], 401);
        }

        $suggestions = GoiYViecLamUngVien::with(['viTriTuyenDung.phongBan', 'viTriTuyenDung.chucVu'])
            ->where('ung_vien_id', $ungVien->id)
            ->where('trang_thai', 'active')
            ->orderByDesc('diem_phu_hop')
            ->limit(12)
            ->get();

        return response()->json([
            'status' => true,
            'data' => $suggestions,
        ]);
    }

    public function refresh(CVAnalysisService $cvAnalysisService)
    {
        $ungVien = Auth::user();

        if (!$ungVien || !($ungVien instanceof \App\Models\UngVien)) {
            return response()->json([
                'status' => false,
                'message' => 'Bạn cần đăng nhập ứng viên.',
            ], 401);
        }

        $latestAnalysis = DanhGiaCVUngTuyen::whereHas('hoSoUngTuyen', function ($query) use ($ungVien) {
                $query->where('ung_vien_id', $ungVien->id);
            })
            ->latest()
            ->first();

        if (!$latestAnalysis) {
            return response()->json([
                'status' => false,
                'message' => 'Chưa có kết quả phân tích CV để gợi ý công việc.',
            ], 404);
        }

        $cvAnalysisService->generateJobSuggestions($latestAnalysis);

        return $this->index();
    }
}
