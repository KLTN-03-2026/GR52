<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HoSoUngTuyen;
use App\Models\DanhGiaCVUngTuyen;
use App\Models\ViTriTuyenDung;
use App\Services\CVAnalysisService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CVAnalysisController extends Controller
{
    protected $cvAnalysisService;

    public function __construct(CVAnalysisService $cvAnalysisService)
    {
        $this->cvAnalysisService = $cvAnalysisService;
    }

    /**
     * Analyze single CV
     * POST /api/cv-analysis/analyze-single
     */
    public function analyzeSingle(Request $request)
    {
        $validated = $request->validate([
            'ho_so_ung_tuyen_id' => 'required|exists:ho_so_ung_tuyens,id',
        ]);

        try {
            $hoSoUngTuyen = HoSoUngTuyen::findOrFail($validated['ho_so_ung_tuyen_id']);

            // Check if CV file exists
            if (!$hoSoUngTuyen->file_cv) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ứng viên chưa cung cấp CV',
                ], 422);
            }

            // Get job requirements
            $jobRequirements = $this->cvAnalysisService->buildJobRequirements(
                $hoSoUngTuyen->viTriTuyenDung
            );

            // Analyze CV
            $analysisResult = $this->cvAnalysisService->analyzeCVFromFile(
                $hoSoUngTuyen->file_cv,
                $jobRequirements
            );

            if (!$analysisResult['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $analysisResult['error'],
                ], 422);
            }

            // Save analysis to database
            $danhGia = $this->saveAnalysis(
                $hoSoUngTuyen,
                $analysisResult['data'],
                $analysisResult['ai_response']
            );
            $this->cvAnalysisService->generateJobSuggestions($danhGia);

            return response()->json([
                'success' => true,
                'message' => 'Phân tích CV thành công',
                'data' => $this->formatAnalysisResponse($danhGia),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Analyze multiple CVs for a job position (batch)
     * POST /api/cv-analysis/analyze-batch
     */
    public function analyzeBatch(Request $request)
    {
        $validated = $request->validate([
            'vi_tri_tuyen_dung_id' => 'required|exists:vi_tri_tuyen_dungs,id',
            'ho_so_ids' => 'nullable|array',
            'ho_so_ids.*' => 'integer|exists:ho_so_ung_tuyens,id',
        ]);

        try {
            $viTriTuyenDung = ViTriTuyenDung::findOrFail($validated['vi_tri_tuyen_dung_id']);

            // Get all applications for this position if no specific IDs provided
            $query = HoSoUngTuyen::where('vi_tri_tuyen_dung_id', $viTriTuyenDung->id)
                ->whereNotNull('file_cv');

            if (!empty($validated['ho_so_ids'])) {
                $query->whereIn('id', $validated['ho_so_ids']);
            }

            $hoSoList = $query->get();

            if ($hoSoList->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy đơn ứng tuyển nào',
                ], 404);
            }

            $jobRequirements = $this->cvAnalysisService->buildJobRequirements($viTriTuyenDung);

            $results = [];
            $successCount = 0;
            $failureCount = 0;

            foreach ($hoSoList as $hoSo) {
                $analysisResult = $this->cvAnalysisService->analyzeCVFromFile(
                    $hoSo->file_cv,
                    $jobRequirements
                );

                if ($analysisResult['success']) {
                    $danhGia = $this->saveAnalysis(
                        $hoSo,
                        $analysisResult['data'],
                        $analysisResult['ai_response']
                    );
                    $this->cvAnalysisService->generateJobSuggestions($danhGia);
                    $results[] = $this->formatAnalysisResponse($danhGia);
                    $successCount++;
                } else {
                    $results[] = [
                        'ho_so_ung_tuyen_id' => $hoSo->id,
                        'success' => false,
                        'error' => $analysisResult['error'],
                    ];
                    $failureCount++;
                }

                // Add small delay to avoid API rate limiting
                sleep(1);
            }

            // Sort by score descending
            usort($results, function ($a, $b) {
                return ($b['tong_diem'] ?? 0) <=> ($a['tong_diem'] ?? 0);
            });

            return response()->json([
                'success' => true,
                'message' => "Phân tích xong. Thành công: {$successCount}, Thất bại: {$failureCount}",
                'stats' => [
                    'total' => count($results),
                    'success' => $successCount,
                    'failure' => $failureCount,
                ],
                'data' => $results,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get analysis for a CV (if already analyzed)
     * GET /api/cv-analysis/{hoSoUngTuyenId}
     */
    public function getAnalysis($hoSoUngTuyenId)
    {
        try {
            $danhGia = DanhGiaCVUngTuyen::where('ho_so_ung_tuyen_id', $hoSoUngTuyenId)
                ->latest()
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => $this->formatAnalysisResponse($danhGia),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy đánh giá',
            ], 404);
        }
    }

    /**
     * Get all analyses for a job position
     * GET /api/cv-analysis/job/{viTriId}
     */
    public function getByPosition($viTriId)
    {
        try {
            $danhGias = DanhGiaCVUngTuyen::where('vi_tri_tuyen_dung_id', $viTriId)
                ->with(['hoSoUngTuyen.ungVien', 'createdBy'])
                ->orderByDesc('tong_diem')
                ->get();

            return response()->json([
                'success' => true,
                'total' => $danhGias->count(),
                'data' => $danhGias->map(fn($d) => $this->formatAnalysisResponse($d)),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Save analysis to database
     */
    protected function saveAnalysis(HoSoUngTuyen $hoSo, array $analysisData, string $aiResponse): DanhGiaCVUngTuyen
    {
        // Check if analysis already exists
        $existing = DanhGiaCVUngTuyen::where('ho_so_ung_tuyen_id', $hoSo->id)->first();

        $data = [
            'ho_so_ung_tuyen_id' => $hoSo->id,
            'vi_tri_tuyen_dung_id' => $hoSo->vi_tri_tuyen_dung_id,
            'created_by' => Auth::guard('sanctum')->id(),
            'tong_diem' => $analysisData['tong_diem'],
            'khuyến_nghị' => $analysisData['khuyến_nghị'],
            'kỹ_năng_phù_hợp' => $analysisData['kỹ_năng_phù_hợp'],
            'kỹ_năng_thiếu' => $analysisData['kỹ_năng_thiếu'],
            'điểm_mạnh' => $analysisData['điểm_mạnh'],
            'điểm_yếu' => $analysisData['điểm_yếu'],
            'tóm_tắt_phân_tích' => $analysisData['tóm_tắt_phân_tích'],
            'ai_response' => $aiResponse,
            'trạng_thái' => 'completed',
        ];

        if ($existing) {
            $existing->update($data);
            return $existing;
        }

        return DanhGiaCVUngTuyen::create($data);
    }

    /**
     * Format analysis response
     */
    protected function formatAnalysisResponse(DanhGiaCVUngTuyen $danhGia): array
    {
        $ungVien = $danhGia->hoSoUngTuyen->ungVien;

        return [
            'id' => $danhGia->id,
            'ho_so_ung_tuyen_id' => $danhGia->ho_so_ung_tuyen_id,
            'ung_vien' => [
                'id' => $ungVien->id,
                'ho_ten' => $ungVien->ho_ten ?? 'N/A',
                'email' => $ungVien->email ?? 'N/A',
                'so_dien_thoai' => $ungVien->so_dien_thoai ?? 'N/A',
            ],
            'tong_diem' => $danhGia->tong_diem,
            'khuyến_nghị' => $danhGia->khuyến_nghị,
            'kỹ_năng_phù_hợp' => $danhGia->kỹ_năng_phù_hợp ?? [],
            'kỹ_năng_thiếu' => $danhGia->kỹ_năng_thiếu ?? [],
            'điểm_mạnh' => $danhGia->điểm_mạnh ?? [],
            'điểm_yếu' => $danhGia->điểm_yếu ?? [],
            'tóm_tắt_phân_tích' => $danhGia->tóm_tắt_phân_tích,
            'created_by' => $danhGia->createdBy->ho_va_ten ?? 'System',
            'created_at' => $danhGia->created_at?->toIso8601String(),
        ];
    }
}
