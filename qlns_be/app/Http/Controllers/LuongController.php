<?php

namespace App\Http\Controllers;

use App\Models\Luong;
use App\Models\NhanVien;
use App\Models\ChamCong;
use App\Models\DonNghiPhep;
use App\Models\KpiNhanVien;
use App\Models\ThuongVaPhat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LuongController extends Controller
{
    // ════════════════════════════════════════════════════════════════════════
    // TÍNH LƯƠNG — Công thức đầy đủ
    // Lương = LCB_thực_tế + thưởng_KPI + thưởng - phạt - khấu_trừ_nghỉ
    // ════════════════════════════════════════════════════════════════════════
    public function tinhLuong(Request $request)
    {
        $v = $request->validate([
            'thang'            => 'required|integer|between:1,12',
            'nam'              => 'required|integer|min:2020',
            'id_nhan_vien'     => 'nullable|exists:nhan_viens,id',
            'so_ngay_chuan'    => 'nullable|integer|min:20|max:31',
            'gio_chuan_ngay'   => 'nullable|numeric|min:4|max:12',
        ]);

        $thang        = $v['thang'];
        $nam          = $v['nam'];
        $soNgayChuan  = $v['so_ngay_chuan']  ?? 26;
        $gioChuan     = $v['gio_chuan_ngay'] ?? 8.0;

        // Chọn danh sách nhân viên cần tính
        $dsNV = NhanVien::where('is_block', 0)
            ->when(isset($v['id_nhan_vien']), fn($q) => $q->where('id', $v['id_nhan_vien']))
            ->get();

        $results = [];
        $errors  = [];

        foreach ($dsNV as $nv) {
            try {
                $row = $this->tinhChoMotNV($nv, $thang, $nam, $soNgayChuan, $gioChuan);
                $results[] = $row;
            } catch (\Exception $e) {
                $errors[] = ['nhan_vien' => $nv->ho_va_ten, 'loi' => $e->getMessage()];
            }
        }

        return response()->json([
            'status'  => true,
            'message' => 'Đã tính lương cho ' . count($results) . ' nhân viên.',
            'data'    => $results,
            'errors'  => $errors,
        ]);
    }

    private function tinhChoMotNV(NhanVien $nv, int $thang, int $nam, int $soNgayChuan, float $gioChuan): array
    {
        $lcb = $nv->luong_co_ban;

        // ── 1. CHẤM CÔNG ─────────────────────────────────────────────────
        $ccRecords = ChamCong::where('id_nhan_vien', $nv->id)
            ->whereMonth('ngay_lam_viec', $thang)
            ->whereYear('ngay_lam_viec', $nam)
            ->where('trang_thai', 2) // Chỉ tính đã xác nhận
            ->get();

        $tongGio        = (float) $ccRecords->sum('so_gio_lam');
        $soNgayLamThucTe = $ccRecords->count();

        // ── 2. NGHỈ PHÉP ─────────────────────────────────────────────────
        $nghiPhep = DonNghiPhep::where('id_nhan_vien', $nv->id)
            ->whereMonth('ngay_bat_dau', $thang)
            ->whereYear('ngay_bat_dau', $nam)
            ->where('trang_thai', 2)
            ->get();

        $soNgayNghiCoPhep = $nghiPhep->sum(function ($don) {
            if ($don->so_ngay_co_luong !== null) {
                return (int) $don->so_ngay_co_luong;
            }

            return in_array($don->loai_nghi, ['phep_nam', 'om'], true) ? (int) $don->so_ngay : 0;
        });

        $soNgayNghiKhongLuong = $nghiPhep->sum(function ($don) {
            if ($don->so_ngay_khong_luong !== null) {
                return (int) $don->so_ngay_khong_luong;
            }

            return in_array($don->loai_nghi, ['phep_nam', 'om'], true) ? 0 : (int) $don->so_ngay;
        });

        // ── 3. KPI ───────────────────────────────────────────────────────
        $kpiRecords  = KpiNhanVien::where('id_nhan_vien', $nv->id)
            ->where('thang', $thang)->where('nam', $nam)
            ->whereNotNull('diem_kpi')
            ->get();

        $tongDiemKpi = (float) $kpiRecords->sum('diem_kpi');

        // Xếp loại KPI tổng hợp dựa trên % hoàn thành trung bình
        $tbPct = $kpiRecords->count() > 0
            ? $kpiRecords->avg('phan_tram_hoan_thanh')
            : null;
        $xepLoaiKpi = $tbPct === null ? null
            : ($tbPct >= 95 ? 'A' : ($tbPct >= 80 ? 'B' : ($tbPct >= 60 ? 'C' : 'D')));

        // Hệ số KPI ảnh hưởng đến phần thưởng
        $heSoKpi = ['A' => 1.2, 'B' => 1.0, 'C' => 0.8, 'D' => 0.5][$xepLoaiKpi] ?? 1.0;

        // ── 4. THƯỞNG / PHẠT ─────────────────────────────────────────────
        $thuongPhat = ThuongVaPhat::where('id_nhan_vien', $nv->id)
            ->where('thang', $thang)->where('nam', $nam)->get();

        $tongThuong = (int) $thuongPhat->where('loai', 'thuong')->sum('so_tien');
        $tongPhat   = (int) $thuongPhat->where('loai', 'phat')->sum('so_tien');

        // ── 5. TÍNH LƯƠNG ────────────────────────────────────────────────
        // Lương theo ngày = LCB / Ngày công chuẩn
        $luongNgay = $soNgayChuan > 0 ? round($lcb / $soNgayChuan, 2) : 0;

        // Lương thực tế = Ngày làm thực tế × Lương ngày
        // (Ngày nghỉ có phép không bị trừ)
        $soNgayTinhLuong = $soNgayLamThucTe + $soNgayNghiCoPhep;
        $luongThucTe     = (int) round(min($soNgayTinhLuong, $soNgayChuan) * $luongNgay);

        // Thưởng KPI = LCB × (Hệ số - 1), chỉ khi có KPI
        $thuongKpi = $xepLoaiKpi !== null ? (int) round($lcb * ($heSoKpi - 1)) : 0;

        // Khấu trừ nghỉ không lương = Số ngày × Lương ngày
        $khauTruNghi = (int) round($soNgayNghiKhongLuong * $luongNgay);

        // Tổng lương trước thuế
        $luongTruocThue = $luongThucTe + $thuongKpi + $tongThuong - $tongPhat - $khauTruNghi;
        $luongTruocThue = max(0, $luongTruocThue);

        // Thuế TNCN (tạm thời = 0, mở rộng sau)
        $thue = 0;

        // Lương thực nhận
        $luongThucNhan = $luongTruocThue - $thue;

        // ── 6. LƯU VÀO DB (upsert theo tháng — không ghi đè nếu đã trả) ─
        $existing = Luong::where('id_nhan_vien', $nv->id)
            ->where('thang', $thang)->where('nam', $nam)->first();

        if ($existing && $existing->trang_thai === 'da_tra') {
            return array_merge(['warning' => 'Đã trả lương, không cập nhật.'], $existing->toArray());
        }

        $saved = Luong::updateOrCreate(
            ['id_nhan_vien' => $nv->id, 'thang' => $thang, 'nam' => $nam],
            [
                'luong_co_ban'              => $lcb,
                'so_ngay_lam_viec_chuan'    => $soNgayChuan,
                'so_gio_lam_thuc_te'        => $tongGio,
                'gio_chuan_ngay'            => $gioChuan,
                'so_ngay_nghi_co_phep'      => $soNgayNghiCoPhep,
                'so_ngay_nghi_khong_luong'  => $soNgayNghiKhongLuong,
                'tong_diem_kpi'             => $tongDiemKpi,
                'xep_loai_kpi'              => $xepLoaiKpi,
                'he_so_kpi'                 => $heSoKpi,
                'tong_thuong'               => $tongThuong,
                'tong_phat'                 => $tongPhat,
                'luong_ngay'                => $luongNgay,
                'luong_thuc_te'             => $luongThucTe,
                'thuong_kpi'                => $thuongKpi,
                'khau_tru_nghi'             => $khauTruNghi,
                'luong_truoc_thue'          => $luongTruocThue,
                'thue_tncn'                 => $thue,
                'luong_thuc_nhan'           => $luongThucNhan,
                'trang_thai'                => 'nhap',
            ]
        );

        return $saved->load('nhanVien:id,ho_va_ten')->toArray();
    }

    // ── LẤY BẢNG LƯƠNG THÁNG ─────────────────────────────────────────────
    public function getLuong(Request $request)
    {
        $v = $request->validate(['thang' => 'required|integer', 'nam' => 'required|integer']);

        $data = Luong::with('nhanVien:id,ho_va_ten,id_phong_ban')
            ->where('thang', $v['thang'])->where('nam', $v['nam'])
            ->when($request->filled('id_nhan_vien'), fn($q) => $q->where('id_nhan_vien', $request->id_nhan_vien))
            ->get();

        $tongHop = [
            'tong_luong_thuc_nhan' => $data->sum('luong_thuc_nhan'),
            'tong_thuong'          => $data->sum('tong_thuong'),
            'tong_phat'            => $data->sum('tong_phat'),
            'so_nhan_vien'         => $data->count(),
        ];

        return response()->json(['status' => true, 'data' => $data, 'tong_hop' => $tongHop]);
    }

    // ── ALIAS GET BẢNG LƯƠNG ────────────────────────────────────────────
    public function getBangLuong(Request $request)
    {
        return $this->getLuong($request);
    }

    // ── DUYỆT / ĐÃ TRẢ LƯƠNG ────────────────────────────────────────────
    public function doiTrangThai(Request $request)
    {
        $v = $request->validate([
            'id'         => 'required|exists:bang_luongs,id',
            'trang_thai' => 'required|in:nhap,da_duyet,da_tra',
        ]);
        $bl = Luong::findOrFail($v['id']);
        $bl->update(['trang_thai' => $v['trang_thai']]);
        return response()->json(['status' => true, 'message' => 'Cập nhật trạng thái thành công.', 'data' => $bl]);
    }

    // ── XUẤT EXCEL / CSV ─────────────────────────────────────────────────
    public function xuatExcel(Request $request)
    {
        $v = $request->validate(['thang' => 'required|integer', 'nam' => 'required|integer']);

        $data = Luong::with('nhanVien:id,ho_va_ten')
            ->where('thang', $v['thang'])->where('nam', $v['nam'])
            ->get();

        $fmt = fn($n) => number_format($n, 0, ',', '.');

        $csv = "\xEF\xBB\xBF"; // UTF-8 BOM
        $csv .= "STT,Nhân Viên,Lương CB,Ngày Làm,Giờ Làm,";
        $csv .= "Nghỉ CL,Nghỉ KL,Điểm KPI,XL KPI,HệSố KPI,";
        $csv .= "Thưởng,Phạt,Thưởng KPI,Khấu Trừ NL,";
        $csv .= "Lương Trước Thuế,Thuế TNCN,Lương Thực Nhận,Trạng Thái\n";

        foreach ($data as $k => $v) {
            $ttMap = ['nhap' => 'Chưa duyệt', 'da_duyet' => 'Đã duyệt', 'da_tra' => 'Đã trả'];
            $csv .= implode(',', [
                $k + 1,
                '"' . ($v->nhan_vien?->ho_va_ten ?? '') . '"',
                $fmt($v->luong_co_ban),
                $v->so_ngay_lam_viec_chuan,
                $v->so_gio_lam_thuc_te,
                $v->so_ngay_nghi_co_phep,
                $v->so_ngay_nghi_khong_luong,
                $v->tong_diem_kpi,
                $v->xep_loai_kpi ?? '—',
                $v->he_so_kpi,
                $fmt($v->tong_thuong),
                $fmt($v->tong_phat),
                $fmt($v->thuong_kpi),
                $fmt($v->khau_tru_nghi),
                $fmt($v->luong_truoc_thue),
                $fmt($v->thue_tncn),
                $fmt($v->luong_thuc_nhan),
                $ttMap[$v->trang_thai] ?? $v->trang_thai,
            ]) . "\n";
        }

        return response($csv, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"bang_luong_T{$v['thang']}_{$v['nam']}.csv\"",
        ]);
    }

    // ── NHÂN VIÊN XEM LƯƠNG CỦA MÌNH (ĐÃ DUYỆT) ──────────────────────
    /**
     * Nhân viên xem bảng lương của bản thân (chỉ hiển thị lương đã duyệt)
     */
    public function viewMyLuong(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'Không tìm thấy thông tin người dùng.'], 401);
        }

        $v = $request->validate([
            'thang' => 'nullable|integer|between:1,12',
            'nam'   => 'nullable|integer|min:2020',
        ]);

        $query = Luong::where('id_nhan_vien', $user->id)
            ->where('trang_thai', 'da_duyet') // Chỉ hiển thị lương đã duyệt
            ->with('nhanVien:id,ho_va_ten');

        // Nếu có chỉ định tháng/năm
        if (isset($v['thang'])) {
            $query->where('thang', $v['thang']);
        }
        if (isset($v['nam'])) {
            $query->where('nam', $v['nam']);
        }

        $data = $query->orderByDesc('nam')->orderByDesc('thang')->get();

        return response()->json([
            'status' => true,
            'data'   => $data,
            'total'  => $data->sum('luong_thuc_nhan'),
        ]);
    }
}
