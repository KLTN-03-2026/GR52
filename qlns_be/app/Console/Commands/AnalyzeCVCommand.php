<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\HoSoUngTuyen;
use App\Models\DanhGiaCVUngTuyen;
use App\Services\CVAnalysisService;
use Illuminate\Support\Facades\Auth;

class AnalyzeCVCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cv:analyze {--single=} {--position=} {--batch}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Analyze CVs using AI. Usage: php artisan cv:analyze --single=1 or php artisan cv:analyze --position=1 --batch';

    protected $cvAnalysisService;

    public function __construct(CVAnalysisService $cvAnalysisService)
    {
        parent::__construct();
        $this->cvAnalysisService = $cvAnalysisService;
    }

    public function handle()
    {
        if ($this->option('single')) {
            $this->analyzeSingle((int) $this->option('single'));
        } elseif ($this->option('position') && $this->option('batch')) {
            $this->analyzeBatch((int) $this->option('position'));
        } else {
            $this->error('Please provide --single=ID or --position=ID --batch');
            return 1;
        }

        return 0;
    }

    protected function analyzeSingle($hoSoId)
    {
        $hoSo = HoSoUngTuyen::find($hoSoId);

        if (!$hoSo) {
            $this->error("Hồ sơ không tìm thấy: {$hoSoId}");
            return;
        }

        if (!$hoSo->file_cv) {
            $this->error("Hồ sơ không có CV: {$hoSoId}");
            return;
        }

        $this->info("Analyzing CV for: {$hoSo->ungVien->ho_ten}");
        $this->info("File: {$hoSo->file_cv}");

        $jobRequirements = app(CVAnalysisService::class)->buildJobRequirements(
            $hoSo->viTriTuyenDung
        );

        $result = $this->cvAnalysisService->analyzeCVFromFile(
            $hoSo->file_cv,
            $jobRequirements
        );

        if ($result['success']) {
            $analysis = DanhGiaCVUngTuyen::create([
                'ho_so_ung_tuyen_id' => $hoSo->id,
                'vi_tri_tuyen_dung_id' => $hoSo->vi_tri_tuyen_dung_id,
                'created_by' => 1, // System user
                'tong_diem' => $result['data']['tong_diem'],
                'khuyến_nghị' => $result['data']['khuyến_nghị'],
                'kỹ_năng_phù_hợp' => $result['data']['kỹ_năng_phù_hợp'],
                'kỹ_năng_thiếu' => $result['data']['kỹ_năng_thiếu'],
                'điểm_mạnh' => $result['data']['điểm_mạnh'],
                'điểm_yếu' => $result['data']['điểm_yếu'],
                'tóm_tắt_phân_tích' => $result['data']['tóm_tắt_phân_tích'],
                'ai_response' => $result['ai_response'],
                'trạng_thái' => 'completed',
            ]);

            $this->info("✅ Analysis saved!");
            $this->line("Score: {$analysis->tong_diem}");
            $this->line("Recommendation: {$analysis->khuyến_nghị}");
        } else {
            $this->error("❌ Error: {$result['error']}");
        }
    }

    protected function analyzeBatch($viTriId)
    {
        $hoSoList = HoSoUngTuyen::where('vi_tri_tuyen_dung_id', $viTriId)
            ->whereNotNull('file_cv')
            ->get();

        if ($hoSoList->isEmpty()) {
            $this->error("No applications found for position: {$viTriId}");
            return;
        }

        $this->info("Analyzing {$hoSoList->count()} applications...");

        $viTriTuyenDung = $hoSoList->first()->viTriTuyenDung;
        $jobRequirements = $this->cvAnalysisService->buildJobRequirements($viTriTuyenDung);

        $bar = $this->output->createProgressBar($hoSoList->count());
        $bar->start();

        $successCount = 0;
        $failureCount = 0;

        foreach ($hoSoList as $hoSo) {
            $result = $this->cvAnalysisService->analyzeCVFromFile(
                $hoSo->file_cv,
                $jobRequirements
            );

            if ($result['success']) {
                DanhGiaCVUngTuyen::create([
                    'ho_so_ung_tuyen_id' => $hoSo->id,
                    'vi_tri_tuyen_dung_id' => $hoSo->vi_tri_tuyen_dung_id,
                    'created_by' => 1,
                    'tong_diem' => $result['data']['tong_diem'],
                    'khuyến_nghị' => $result['data']['khuyến_nghị'],
                    'kỹ_năng_phù_hợp' => $result['data']['kỹ_năng_phù_hợp'],
                    'kỹ_năng_thiếu' => $result['data']['kỹ_năng_thiếu'],
                    'điểm_mạnh' => $result['data']['điểm_mạnh'],
                    'điểm_yếu' => $result['data']['điểm_yếu'],
                    'tóm_tắt_phân_tích' => $result['data']['tóm_tắt_phân_tích'],
                    'ai_response' => $result['ai_response'],
                    'trạng_thái' => 'completed',
                ]);
                $successCount++;
            } else {
                $failureCount++;
            }

            $bar->advance();
            sleep(1); // Rate limiting
        }

        $bar->finish();
        $this->line("");
        $this->info("✅ Analysis complete!");
        $this->line("Success: {$successCount}");
        $this->line("Failed: {$failureCount}");
    }
}
