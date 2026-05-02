<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\DanhGiaCVUngTuyen;
use App\Models\GoiYViecLamUngVien;
use App\Models\HoSoUngTuyen;
use App\Models\ViTriTuyenDung;
use Exception;

class CVAnalysisService
{
    protected $client;
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.groq.api_key');
        $this->baseUrl = $this->normalizeBaseUrl(config('services.groq.base_url'));
    }

    /**
     * Analyze CV from file path
     */
    public function analyzeCVFromFile(string $filePath, array $jobRequirements): array
    {
        try {
            $cvText = $this->extractDocumentText($filePath);

            if (strlen($cvText) < 100) {
                throw new Exception('CV không đọc được hoặc quá ngắn');
            }

            return $this->analyzeCV($cvText, $jobRequirements);
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'trạng_thái' => 'error',
            ];
        }
    }

    /**
     * Extract text from PDF file
     */
    protected function extractDocumentText(string $filePath): string
    {
        try {
            // Check if file exists
            if (!Storage::disk('public')->exists($filePath)) {
                throw new Exception("File CV không tìm thấy: {$filePath}");
            }

            $fullPath = Storage::disk('public')->path($filePath);
            $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

            if ($extension !== 'pdf') {
                throw new Exception('AI hiện chỉ hỗ trợ đọc CV định dạng PDF');
            }

            // Use smalot/pdfparser to extract text
            if (class_exists('\Smalot\PdfParser\Parser')) {
                $parser = new \Smalot\PdfParser\Parser();
                $pdf = $parser->parseFile($fullPath);
                $pages = $pdf->getPages();

                $text = '';
                foreach ($pages as $page) {
                    $text .= $page->getText();
                }

                return trim($text);
            } else {
                // Fallback: Try to read PDF using alternative method
                throw new Exception("PDF Parser library not installed. Run: composer require smalot/pdfparser");
            }
        } catch (Exception $e) {
            throw new Exception("Lỗi khi trích xuất PDF: " . $e->getMessage());
        }
    }

    /**
     * Analyze CV with AI
     */
    protected function analyzeCV(string $cvText, array $jobRequirements): array
    {
        try {
            if (empty($this->apiKey)) {
                throw new Exception('Chưa cấu hình GROQ_API_KEY trong file .env');
            }

            $prompt = $this->buildPrompt($cvText, $jobRequirements);

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/chat/completions", [
                'model' => 'llama-3.1-8b-instant',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0,
                'max_tokens' => 2000,
            ]);

            if (!$response->successful()) {
                throw new Exception("Lỗi API: " . $response->body());
            }

            $rawContent = $response->json('choices.0.message.content');

            // Parse and clean AI output
            $analysisData = $this->parseAIResponse($rawContent);

            return [
                'success' => true,
                'data' => $analysisData,
                'ai_response' => $rawContent,
                'trạng_thái' => 'completed',
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'trạng_thái' => 'error',
            ];
        }
    }

    protected function normalizeBaseUrl(?string $baseUrl): string
    {
        $baseUrl = trim((string) $baseUrl);

        if ($baseUrl === '' || !str_starts_with($baseUrl, 'http')) {
            return 'https://api.groq.com/openai/v1';
        }

        return rtrim($baseUrl, '/');
    }

    /**
     * Build AI prompt
     */
    protected function buildPrompt(string $cvText, array $jobRequirements): string
    {
        $requirementsJson = json_encode([
            'title' => $jobRequirements['title'] ?? 'Vị trí chưa xác định',
            'requirements' => $jobRequirements['requirements'] ?? [],
            'description' => $jobRequirements['description'] ?? '',
        ], JSON_UNESCAPED_UNICODE);

        return <<<PROMPT
Bạn là Senior HR Consultant + Technical Recruiter.

=== VỊ TRÍ TUYỂN DỤNG ===
{$requirementsJson}

=== CV ỨNG VIÊN ===
{$cvText}

=== YÊU CẦU PHÂN TÍCH ===
1. Đánh giá mức độ phù hợp của ứng viên
2. Chấm điểm từ 0-100
3. Phân tích kỹ năng và kinh nghiệm
4. Liệt kê điểm mạnh, điểm yếu
5. Đưa ra khuyến nghị (Phù hợp / Cần xem xét / Không phù hợp)

⚠️ QUAN TRỌNG: Trả lời CHÍNH XÁC định dạng JSON sau (không thêm bất cứ gì khác):

{
  "candidate_name": "Họ và tên ứng viên",
  "total_score": 85,
  "recommendation": "Phù hợp",
  "matched_skills": ["Kỹ năng 1", "Kỹ năng 2"],
  "missing_skills": ["Kỹ năng thiếu 1"],
  "strengths": ["Điểm mạnh 1", "Điểm mạnh 2"],
  "weaknesses": ["Điểm yếu 1"],
  "summary": "Tóm tắt phân tích chi tiết..."
}
PROMPT;
    }

    /**
     * Parse and clean AI response
     */
    protected function parseAIResponse(string $rawResponse): array
    {
        try {
            // Extract JSON from response
            preg_match('/\{[\s\S]*\}/', $rawResponse, $matches);

            if (empty($matches)) {
                throw new Exception('Không tìm thấy JSON trong phản hồi AI');
            }

            $jsonStr = $matches[0];

            // Fix common JSON issues
            $jsonStr = $this->fixJsonString($jsonStr);

            $data = json_decode($jsonStr, true);

            if (!$data) {
                throw new Exception('JSON không hợp lệ: ' . json_last_error_msg());
            }

            // Validate required fields
            return $this->validateAnalysisData($data);
        } catch (Exception $e) {
            throw new Exception("Lỗi khi phân tích phản hồi AI: " . $e->getMessage());
        }
    }

    /**
     * Fix common JSON formatting issues
     */
    protected function fixJsonString(string $json): string
    {
        // Remove markdown code blocks
        $json = str_replace('```json', '', $json);
        $json = str_replace('```', '', $json);

        // Fix escaped quotes issues
        $json = preg_replace('/\\\\"/', '"', $json);

        // Fix incomplete JSON (missing closing brace)
        $openCount = substr_count($json, '{') - substr_count($json, '\\{');
        $closeCount = substr_count($json, '}') - substr_count($json, '\\}');

        if ($openCount > $closeCount) {
            $json .= str_repeat('}', $openCount - $closeCount);
        }

        return trim($json);
    }

    /**
     * Validate and normalize analysis data
     */
    protected function validateAnalysisData(array $data): array
    {
        return [
            'candidate_name' => $data['candidate_name'] ?? 'N/A',
            'tong_diem' => (int) ($data['total_score'] ?? 0),
            'khuyến_nghị' => $data['recommendation'] ?? 'Cần xem xét',
            'kỹ_năng_phù_hợp' => $data['matched_skills'] ?? [],
            'kỹ_năng_thiếu' => $data['missing_skills'] ?? [],
            'điểm_mạnh' => $data['strengths'] ?? [],
            'điểm_yếu' => $data['weaknesses'] ?? [],
            'tóm_tắt_phân_tích' => $data['summary'] ?? '',
        ];
    }

    /**
     * Build job requirements array from ViTriTuyenDung model
     */
    public function buildJobRequirements($viTriTuyenDung): array
    {
        return [
            'title' => $viTriTuyenDung->tieu_de ?? optional($viTriTuyenDung->chucVu)->ten_chuc_vu ?? 'Vị trí chưa xác định',
            'requirements' => array_filter([
                optional($viTriTuyenDung->chucVu)->ten_chuc_vu,
                optional($viTriTuyenDung->phongBan)->ten_phong_ban,
            ]),
            'description' => $viTriTuyenDung->mo_ta ?? '',
        ];
    }

    public function generateJobSuggestions(DanhGiaCVUngTuyen $danhGia): int
    {
        $danhGia->loadMissing('hoSoUngTuyen.ungVien');
        $ungVien = $danhGia->hoSoUngTuyen?->ungVien;

        if (!$ungVien) {
            return 0;
        }

        $appliedJobIds = HoSoUngTuyen::where('ung_vien_id', $ungVien->id)
            ->pluck('vi_tri_tuyen_dung_id')
            ->all();

        $keywords = $this->buildSuggestionKeywords($danhGia);
        $jobs = ViTriTuyenDung::with(['phongBan', 'chucVu'])
            ->where('tinh_trang', 1)
            ->whereNotIn('id', $appliedJobIds)
            ->get();

        $saved = 0;
        foreach ($jobs as $job) {
            $matched = $this->matchJobKeywords($job, $keywords);
            $score = min(100, (int) round(($danhGia->tong_diem * 0.35) + (count($matched) * 13)));

            if ($score < 35) {
                continue;
            }

            GoiYViecLamUngVien::updateOrCreate(
                [
                    'ung_vien_id' => $ungVien->id,
                    'vi_tri_tuyen_dung_id' => $job->id,
                ],
                [
                    'danh_gia_cv_ung_tuyen_id' => $danhGia->id,
                    'diem_phu_hop' => $score,
                    'ly_do' => $this->buildSuggestionReason($job, $matched, $score),
                    'ky_nang_phu_hop' => array_values($matched),
                    'trang_thai' => 'active',
                ]
            );
            $saved++;
        }

        return $saved;
    }

    protected function buildSuggestionKeywords(DanhGiaCVUngTuyen $danhGia): array
    {
        $items = array_merge(
            $danhGia->kỹ_năng_phù_hợp ?? [],
            $danhGia->điểm_mạnh ?? [],
            preg_split('/[\s,.;:\/\-\n\r]+/u', $danhGia->tóm_tắt_phân_tích ?? '') ?: []
        );

        return collect($items)
            ->map(fn($item) => mb_strtolower(trim((string) $item), 'UTF-8'))
            ->filter(fn($item) => mb_strlen($item, 'UTF-8') >= 3)
            ->unique()
            ->take(40)
            ->values()
            ->all();
    }

    protected function matchJobKeywords(ViTriTuyenDung $job, array $keywords): array
    {
        $haystack = mb_strtolower(implode(' ', array_filter([
            $job->tieu_de,
            $job->mo_ta,
            optional($job->phongBan)->ten_phong_ban,
            optional($job->chucVu)->ten_chuc_vu,
        ])), 'UTF-8');

        return array_values(array_unique(array_filter($keywords, function ($keyword) use ($haystack) {
            return str_contains($haystack, $keyword);
        })));
    }

    protected function buildSuggestionReason(ViTriTuyenDung $job, array $matched, int $score): string
    {
        if (!empty($matched)) {
            return 'Phù hợp ' . $score . '% theo kỹ năng: ' . implode(', ', array_slice($matched, 0, 5));
        }

        return 'Phù hợp ' . $score . '% theo hồ sơ CV đã phân tích';
    }
}
