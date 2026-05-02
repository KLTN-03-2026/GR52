<?php

namespace App\Http\Controllers;

use App\Models\TieuChiKpi;
use App\Models\KpiNhanVien;
use App\Models\NhanVien;
use Illuminate\Http\Request;

class KpiNhanVienController extends Controller
{
    // ── TIÊU CHÍ KPI (Template) ──────────────────────────────────────────────

    public function danhSachTieuChi()
    {
        return response()->json([
            'status' => true,
            'data'   => TieuChiKpi::orderBy('created_at', 'desc')->get(),
        ]);
    }

    public function taotTieuChi(Request $request)
    {
        $v = $request->validate([
            'ten_tieu_chi' => 'required|string|max:200',
            'mo_ta'        => 'nullable|string',
            'trong_so'     => 'required|numeric|min:0|max:100',
            'muc_tieu'     => 'required|numeric|min:0',
            'don_vi'       => 'nullable|string|max:50',
        ]);
        return response()->json([
            'status'  => true,
            'message' => 'Tạo tiêu chí KPI thành công.',
            'data'    => TieuChiKpi::create($v),
        ], 201);
    }

    public function suaTieuChi(Request $request)
    {
        $tc = TieuChiKpi::findOrFail($request->id);
        $v  = $request->validate([
            'ten_tieu_chi' => 'string|max:200',
            'mo_ta'        => 'nullable|string',
            'trong_so'     => 'numeric|min:0|max:100',
            'muc_tieu'     => 'numeric|min:0',
            'don_vi'       => 'nullable|string|max:50',
            'tinh_trang'   => 'integer|in:0,1',
        ]);
        $tc->update($v);
        return response()->json(['status' => true, 'message' => 'Cập nhật tiêu chí thành công.', 'data' => $tc]);
    }

    public function xoaTieuChi(Request $request)
    {
        $tc = TieuChiKpi::findOrFail($request->id);
        if ($tc->kpiNhanViens()->exists()) {
            return response()->json(['status' => false, 'message' => 'Không thể xóa vì đã gán cho nhân viên.']);
        }
        $tc->delete();
        return response()->json(['status' => true, 'message' => 'Đã xóa tiêu chí KPI.']);
    }

    // ── KPI NHÂN VIÊN ────────────────────────────────────────────────────────

    public function danhSachKpiNV(Request $request)
    {
        $v = $request->validate([
            'thang' => 'required|integer|between:1,12',
            'nam'   => 'required|integer|min:2020',
        ]);

        $data = KpiNhanVien::with(['nhanVien:id,ho_va_ten', 'tieuChi'])
            ->where('thang', $v['thang'])
            ->where('nam',   $v['nam'])
            ->when($request->filled('id_nhan_vien'), fn($q) => $q->where('id_nhan_vien', $request->id_nhan_vien))
            ->get();

        return response()->json(['status' => true, 'data' => $data]);
    }

    public function ganKpiNV(Request $request)
    {
        $v = $request->validate([
            'id_nhan_vien' => 'required|exists:nhan_viens,id',
            'id_tieu_chi'  => 'required|exists:tieu_chi_kpis,id',
            'thang'        => 'required|integer|between:1,12',
            'nam'          => 'required|integer|min:2020',
            'muc_tieu'     => 'nullable|numeric|min:0',
        ]);

        $tc = TieuChiKpi::findOrFail($v['id_tieu_chi']);

        $kpi = KpiNhanVien::updateOrCreate(
            [
                'id_nhan_vien' => $v['id_nhan_vien'],
                'id_tieu_chi' => $v['id_tieu_chi'],
                'thang' => $v['thang'],
                'nam' => $v['nam']
            ],
            ['muc_tieu' => $v['muc_tieu'] ?? $tc->muc_tieu]
        );

        return response()->json([
            'status'  => true,
            'message' => 'Gán KPI thành công.',
            'data'    => $kpi->load(['nhanVien:id,ho_va_ten', 'tieuChi']),
        ]);
    }

    public function nhapKetQua(Request $request)
    {
        $v = $request->validate([
            'id'               => 'required|exists:kpi_nhan_viens,id',
            'ket_qua_thuc_te'  => 'required|numeric|min:0',
            'ghi_chu'          => 'nullable|string|max:500',
        ]);

        $kpi = KpiNhanVien::findOrFail($v['id']);

        // Tính % hoàn thành
        $pct   = $kpi->muc_tieu > 0 ? round(($v['ket_qua_thuc_te'] / $kpi->muc_tieu) * 100, 2) : 0;
        // Tính điểm KPI = trọng số × % / 100
        $diem  = round($kpi->tieuChi->trong_so * $pct / 100, 2);
        // Xếp loại
        $xepLoai = $pct >= 95 ? 'A' : ($pct >= 80 ? 'B' : ($pct >= 60 ? 'C' : 'D'));

        $kpi->update([
            'ket_qua_thuc_te'       => $v['ket_qua_thuc_te'],
            'phan_tram_hoan_thanh'  => $pct,
            'diem_kpi'              => $diem,
            'xep_loai'              => $xepLoai,
            'ghi_chu'               => $v['ghi_chu'] ?? null,
        ]);

        return response()->json([
            'status'  => true,
            'message' => "Nhập kết quả thành công. Xếp loại: $xepLoai ($pct%)",
            'data'    => $kpi->fresh()->load(['nhanVien:id,ho_va_ten', 'tieuChi']),
        ]);
    }

    public function xoaKpiNV(Request $request)
    {
        KpiNhanVien::findOrFail($request->id)->delete();
        return response()->json(['status' => true, 'message' => 'Đã xóa bản ghi KPI.']);
    }


    public function tongDiemKpi(int $idNV, int $thang, int $nam): float
    {
        return (float) KpiNhanVien::where('id_nhan_vien', $idNV)
            ->where('thang', $thang)->where('nam', $nam)
            ->sum('diem_kpi');
    }

    // ── NHÂN VIÊN XEM KPI CỦA MÌNH ────────────────────────────────────
    /**
     * Nhân viên xem KPI của bản thân
     */
    public function viewMyKpi(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'Không tìm thấy thông tin người dùng.'], 401);
        }

        $v = $request->validate([
            'thang' => 'nullable|integer|between:1,12',
            'nam'   => 'nullable|integer|min:2020',
        ]);

        $query = KpiNhanVien::where('id_nhan_vien', $user->id)
            ->with(['tieuChi:id,ten_tieu_chi,trong_so,don_vi']);

        // Nếu có chỉ định tháng/năm
        if (isset($v['thang'])) {
            $query->where('thang', $v['thang']);
        }
        if (isset($v['nam'])) {
            $query->where('nam', $v['nam']);
        }

        $data = $query->orderByDesc('nam')->orderByDesc('thang')->get();

        // Tính tổng điểm và xếp loại
        $groupedByMonth = $data->groupBy(fn($item) => "{$item->nam}-{$item->thang}");
        $summary = [];

        foreach ($groupedByMonth as $key => $items) {
            $total_diem = $items->sum('diem_kpi');
            $items_with_rating = $items->whereNotNull('xep_loai');
            $avg_rating = $items_with_rating->count() > 0
                ? $this->calculateOverallRating($items_with_rating->pluck('xep_loai')->toArray())
                : null;

            [$nam, $thang] = explode('-', $key);
            $summary[] = [
                'thang'          => (int)$thang,
                'nam'            => (int)$nam,
                'tong_diem'      => $total_diem,
                'xep_loai_chung' => $avg_rating,
            ];
        }

        return response()->json([
            'status'  => true,
            'data'    => $data,
            'summary' => $summary,
        ]);
    }

    /**
     * Tính xếp loại tổng hợp từ danh sách xếp loại
     */
    private function calculateOverallRating(array $ratings): string
    {
        if (empty($ratings)) return 'D';

        $score = [
            'A' => 4,
            'B' => 3,
            'C' => 2,
            'D' => 1,
        ];

        $total = array_reduce($ratings, fn($sum, $r) => $sum + ($score[$r] ?? 0), 0);
        $avg = $total / count($ratings);

        return $avg >= 3.5 ? 'A' : ($avg >= 2.5 ? 'B' : ($avg >= 1.5 ? 'C' : 'D'));
    }
}
