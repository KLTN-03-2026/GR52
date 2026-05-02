<?php

namespace App\Http\Controllers;

use App\Models\NhanVien;
use App\Models\PhongBan;
use App\Models\ChucVu;
use App\Models\ChamCong;
use App\Models\DonNghiPhep;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ThongKeController extends Controller
{
    // ════════════════════════════════════════════════════════════════════════
    // 1. THỐNG KÊ CHẤM CÔNG — có bộ lọc ngày/tháng/năm
    // GET /api/admin/thong-ke/cham-cong
    // Params: loai (ngay|thang|nam), ngay, thang, nam
    // ════════════════════════════════════════════════════════════════════════
    public function chamCong(Request $request)
    {
        $validated = $request->validate([
            'loai'  => 'nullable|in:ngay,thang,nam',
            'ngay'  => 'nullable|date',
            'thang' => 'nullable|integer|between:1,12',
            'nam'   => 'nullable|integer|min:2020',
        ]);

        $loai  = $validated['loai'] ?? 'thang';
        $thang = $validated['thang'] ?? now()->month;
        $nam   = $validated['nam']   ?? now()->year;
        $ngay  = $validated['ngay']  ?? now()->toDateString();

        // ── Tổng quan ─────────────────────────────────────────────────────
        $query = ChamCong::query();
        if ($loai === 'ngay')  $query->whereDate('ngay_lam_viec', $ngay);
        if ($loai === 'thang') $query->whereMonth('ngay_lam_viec', $thang)->whereYear('ngay_lam_viec', $nam);
        if ($loai === 'nam')   $query->whereYear('ngay_lam_viec', $nam);

        $tongHop = [
            'tong_luot'    => (clone $query)->count(),
            'da_xac_nhan'  => (clone $query)->where('trang_thai', 2)->count(),
            'cho_xac_nhan' => (clone $query)->where('trang_thai', 1)->count(),
            'dang_lam'     => (clone $query)->where('trang_thai', 0)->count(),
            'vang_mat'     => (clone $query)->where('trang_thai', 3)->count(),
            'tong_gio'     => round((clone $query)->sum('so_gio_lam'), 1),
            'tb_gio_ngay'  => round((clone $query)->avg('so_gio_lam'), 2),
        ];

        // ── Biểu đồ theo ngày (nếu lọc tháng) ────────────────────────────
        $bieuDo = [];
        if ($loai === 'thang') {
            $data = ChamCong::select(
                DB::raw('CAST(strftime(\'%d\', ngay_lam_viec) AS INTEGER) as ngay'),
                DB::raw('COUNT(*) as so_luot'),
                DB::raw('ROUND(SUM(so_gio_lam), 1) as tong_gio')
            )
                ->whereMonth('ngay_lam_viec', $thang)
                ->whereYear('ngay_lam_viec', $nam)
                ->groupBy(DB::raw('CAST(strftime(\'%d\', ngay_lam_viec) AS INTEGER)'))
                ->orderBy('ngay')
                ->get();

            $soNgayTrongThang = Carbon::createFromDate($nam, $thang, 1)->daysInMonth;
            for ($i = 1; $i <= $soNgayTrongThang; $i++) {
                $found = $data->firstWhere('ngay', $i);
                $bieuDo[] = [
                    'label'    => "$i/$thang",
                    'so_luot'  => $found ? $found->so_luot : 0,
                    'tong_gio' => $found ? $found->tong_gio : 0,
                ];
            }
        }

        // Biểu đồ theo tháng (nếu lọc năm)
        if ($loai === 'nam') {
            $data = ChamCong::select(
                DB::raw('CAST(strftime(\'%m\', ngay_lam_viec) AS INTEGER) as thang'),
                DB::raw('COUNT(*) as so_luot'),
                DB::raw('ROUND(SUM(so_gio_lam), 1) as tong_gio')
            )
                ->whereYear('ngay_lam_viec', $nam)
                ->groupBy(DB::raw('CAST(strftime(\'%m\', ngay_lam_viec) AS INTEGER)'))
                ->orderBy('thang')
                ->get();

            for ($i = 1; $i <= 12; $i++) {
                $found = $data->firstWhere('thang', $i);
                $bieuDo[] = [
                    'label'    => "T$i",
                    'so_luot'  => $found ? $found->so_luot : 0,
                    'tong_gio' => $found ? $found->tong_gio : 0,
                ];
            }
        }

        // ── Phân bố ca làm ────────────────────────────────────────────────
        $phanBoCA = [
            'sang'  => (clone $query)->where('ca_lam', 'sang')->count(),
            'chieu' => (clone $query)->where('ca_lam', 'chieu')->count(),
            'toi'   => (clone $query)->where('ca_lam', 'toi')->count(),
        ];

        // ── Top 5 nhân viên nhiều giờ nhất ────────────────────────────────
        $topNV = ChamCong::with('nhanVien:id,ho_va_ten')
            ->select('id_nhan_vien', DB::raw('ROUND(SUM(so_gio_lam),1) as tong_gio'), DB::raw('COUNT(*) as so_luot'))
            ->when($loai === 'thang', fn($q) => $q->whereMonth('ngay_lam_viec', $thang)->whereYear('ngay_lam_viec', $nam))
            ->when($loai === 'nam',   fn($q) => $q->whereYear('ngay_lam_viec', $nam))
            ->when($loai === 'ngay',  fn($q) => $q->whereDate('ngay_lam_viec', $ngay))
            ->groupBy('id_nhan_vien')
            ->orderByDesc('tong_gio')
            ->limit(5)
            ->get();

        return response()->json([
            'status'   => true,
            'bo_loc'   => compact('loai', 'thang', 'nam', 'ngay'),
            'tong_hop' => $tongHop,
            'bieu_do'  => $bieuDo,
            'phan_bo_ca' => $phanBoCA,
            'top_nhan_vien' => $topNV,
        ]);
    }

    // ════════════════════════════════════════════════════════════════════════
    // 2. THỐNG KÊ NGHỈ PHÉP — có bộ lọc tháng/năm
    // GET /api/admin/thong-ke/nghi-phep
    // Params: thang, nam
    // ════════════════════════════════════════════════════════════════════════
    public function nghiPhep(Request $request)
    {
        $validated = $request->validate([
            'thang' => 'nullable|integer|between:1,12',
            'nam'   => 'nullable|integer|min:2020',
        ]);

        $thang = $validated['thang'] ?? now()->month;
        $nam   = $validated['nam']   ?? now()->year;

        $q = DonNghiPhep::whereMonth('ngay_bat_dau', $thang)->whereYear('ngay_bat_dau', $nam);

        // ── Tổng quan ─────────────────────────────────────────────────────
        $tongHop = [
            'tong_don'       => (clone $q)->count(),
            'cho_duyet'      => (clone $q)->where('trang_thai', 1)->count(),
            'da_duyet'       => (clone $q)->where('trang_thai', 2)->count(),
            'tu_choi'        => (clone $q)->where('trang_thai', 3)->count(),
            'tong_ngay_nghi' => (clone $q)->where('trang_thai', 2)->sum('so_ngay'),
        ];

        // ── Phân loại nghỉ ────────────────────────────────────────────────
        $phanLoai = DonNghiPhep::whereMonth('ngay_bat_dau', $thang)
            ->whereYear('ngay_bat_dau', $nam)
            ->where('trang_thai', 2)
            ->select('loai_nghi', DB::raw('COUNT(*) as so_don'), DB::raw('SUM(so_ngay) as tong_ngay'))
            ->groupBy('loai_nghi')
            ->get();

        // ── Biểu đồ 12 tháng trong năm ────────────────────────────────────
        $bieuDoNam = [];
        for ($i = 1; $i <= 12; $i++) {
            $so = DonNghiPhep::whereMonth('ngay_bat_dau', $i)
                ->whereYear('ngay_bat_dau', $nam)
                ->where('trang_thai', 2)
                ->sum('so_ngay');
            $bieuDoNam[] = ['label' => "T$i", 'tong_ngay' => $so ?: 0];
        }

        // ── Top nhân viên nghỉ nhiều nhất tháng ──────────────────────────
        $topNghiNhieu = DonNghiPhep::with('nhanVien:id,ho_va_ten')
            ->whereMonth('ngay_bat_dau', $thang)
            ->whereYear('ngay_bat_dau', $nam)
            ->where('trang_thai', 2)
            ->select('id_nhan_vien', DB::raw('SUM(so_ngay) as tong_ngay'), DB::raw('COUNT(*) as so_don'))
            ->groupBy('id_nhan_vien')
            ->orderByDesc('tong_ngay')
            ->limit(5)
            ->get();

        return response()->json([
            'status'        => true,
            'bo_loc'        => compact('thang', 'nam'),
            'tong_hop'      => $tongHop,
            'phan_loai'     => $phanLoai,
            'bieu_do_nam'   => $bieuDoNam,
            'top_nghi_nhieu' => $topNghiNhieu,
        ]);
    }

    // ════════════════════════════════════════════════════════════════════════
    // 3. TỔNG QUAN NHÂN SỰ
    // GET /api/admin/thong-ke/tong-quan
    // ════════════════════════════════════════════════════════════════════════
    public function tongQuan()
    {
        $thangNay = now()->month;
        $namNay   = now()->year;

        // ── Số liệu tổng hợp ──────────────────────────────────────────────
        $tongHop = [
            'tong_nhan_vien'  => NhanVien::count(),
            'dang_lam_viec'   => NhanVien::where('is_block', 0)->count(),
            'da_nghi'         => NhanVien::where('is_block', 1)->count(),
            'moi_thang_nay'   => NhanVien::whereMonth('created_at', $thangNay)->whereYear('created_at', $namNay)->count(),
        ];

        // ── Biểu đồ nhân viên theo tháng trong năm ────────────────────────
        $tuyenDungTheoThang = [];
        for ($i = 1; $i <= 12; $i++) {
            $so = NhanVien::whereMonth('created_at', $i)->whereYear('created_at', $namNay)->count();
            $tuyenDungTheoThang[] = ['label' => "T$i", 'so_luong' => $so];
        }

        // ── Hợp đồng sắp hết hạn (trong 30 ngày) ─────────────────────────
        // Giả sử có bảng chi_tiet_hop_dongs
        // $sapHetHan = ChiTietHopDong::whereBetween('ngay_ket_thuc', [now(), now()->addDays(30)])->count();

        return response()->json([
            'status'                => true,
            'tong_hop'              => $tongHop,
            'tuyen_dung_theo_thang' => $tuyenDungTheoThang,
        ]);
    }

    // ════════════════════════════════════════════════════════════════════════
    // 4. NHÂN SỰ THEO PHÒNG BAN
    // GET /api/admin/thong-ke/phong-ban
    // ════════════════════════════════════════════════════════════════════════
    public function nhAnSuPhongBan()
    {
        $data = PhongBan::withCount([
            'nhanViens as tong_nv',
            'nhanViens as dang_lam' => fn($q) => $q->where('is_block', 0),
        ])
            ->where('tinh_trang', 1)
            ->get()
            ->map(fn($pb) => [
                'id'         => $pb->id,
                'ten'        => $pb->ten_phong_ban,
                'tong_nv'    => $pb->tong_nv,
                'dang_lam'   => $pb->dang_lam,
                'da_nghi'    => $pb->tong_nv - $pb->dang_lam,
            ])
            ->sortByDesc('tong_nv')
            ->values();

        return response()->json([
            'status' => true,
            'data'   => $data,
        ]);
    }

    // ════════════════════════════════════════════════════════════════════════
    // 5. NHÂN SỰ THEO CHỨC VỤ
    // GET /api/admin/thong-ke/chuc-vu
    // ════════════════════════════════════════════════════════════════════════
    public function nhAnSuChucVu()
    {
        $data = ChucVu::withCount([
            'nhanViens as tong_nv',
            'nhanViens as dang_lam' => fn($q) => $q->where('is_block', 0),
        ])
            ->where('tinh_trang', 1)
            ->get()
            ->map(fn($cv) => [
                'id'       => $cv->id,
                'ten'      => $cv->ten_chuc_vu,
                'tong_nv'  => $cv->tong_nv,
                'dang_lam' => $cv->dang_lam,
            ])
            ->sortByDesc('tong_nv')
            ->values();

        return response()->json([
            'status' => true,
            'data'   => $data,
        ]);
    }

    // ════════════════════════════════════════════════════════════════════════
    // 6. CẢNH BÁO NHÂN SỰ
    // GET /api/admin/thong-ke/canh-bao
    // ════════════════════════════════════════════════════════════════════════
    public function canhBao(Request $request)
    {
        $thang = $request->input('thang', now()->month);
        $nam   = $request->input('nam',   now()->year);

        // ── Cảnh báo 1: Nhân viên nghỉ quá nhiều (>= 3 ngày/tháng) ───────
        $NGUONG_NGHI = 3;
        $nghiQuaNhieu = DonNghiPhep::with('nhanVien:id,ho_va_ten,id_phong_ban')
            ->whereMonth('ngay_bat_dau', $thang)
            ->whereYear('ngay_bat_dau', $nam)
            ->where('trang_thai', 2)
            ->select('id_nhan_vien', DB::raw('SUM(so_ngay) as tong_ngay'), DB::raw('COUNT(*) as so_don'))
            ->groupBy('id_nhan_vien')
            ->having('tong_ngay', '>=', $NGUONG_NGHI)
            ->orderByDesc('tong_ngay')
            ->get()
            ->map(fn($v) => [
                'id_nhan_vien' => $v->id_nhan_vien,
                'ho_va_ten'    => $v->nhan_vien?->ho_va_ten,
                'tong_ngay'    => $v->tong_ngay,
                'so_don'       => $v->so_don,
                'muc_do'       => $v->tong_ngay >= 6 ? 'cao' : ($v->tong_ngay >= 4 ? 'trung_binh' : 'thap'),
            ]);

        // ── Cảnh báo 2: Nhân viên không check-in hôm nay ──────────────────
        $today        = now()->toDateString();
        $dsNhanVien   = NhanVien::where('is_block', 0)->pluck('id');
        $daCheckIn    = ChamCong::whereDate('ngay_lam_viec', $today)->pluck('id_nhan_vien');
        $chuaCheckIn  = NhanVien::whereIn('id', $dsNhanVien->diff($daCheckIn))
            ->select('id', 'ho_va_ten', 'id_phong_ban')
            ->with('phongBan:id,ten_phong_ban')
            ->get()
            ->map(fn($v) => [
                'id_nhan_vien' => $v->id,
                'ho_va_ten'    => $v->ho_va_ten,
                'phong_ban'    => $v->phongBan?->ten_phong_ban,
            ]);

        // ── Cảnh báo 3: Nhân viên OT bất thường (>= 10h/ngày trung bình) ─
        $NGUONG_OT   = 10.0;
        $otBatThuong = ChamCong::with('nhanVien:id,ho_va_ten')
            ->whereMonth('ngay_lam_viec', $thang)
            ->whereYear('ngay_lam_viec', $nam)
            ->where('trang_thai', 2)
            ->select(
                'id_nhan_vien',
                DB::raw('ROUND(AVG(so_gio_lam), 2) as tb_gio'),
                DB::raw('ROUND(MAX(so_gio_lam), 2) as max_gio'),
                DB::raw('COUNT(*) as so_ngay')
            )
            ->groupBy('id_nhan_vien')
            ->having('tb_gio', '>=', $NGUONG_OT)
            ->orderByDesc('tb_gio')
            ->get()
            ->map(fn($v) => [
                'id_nhan_vien' => $v->id_nhan_vien,
                'ho_va_ten'    => $v->nhan_vien?->ho_va_ten,
                'tb_gio'       => $v->tb_gio,
                'max_gio'      => $v->max_gio,
                'so_ngay'      => $v->so_ngay,
                'muc_do'       => $v->tb_gio >= 14 ? 'cao' : ($v->tb_gio >= 12 ? 'trung_binh' : 'thap'),
            ]);

        return response()->json([
            'status'          => true,
            'bo_loc'          => compact('thang', 'nam'),
            'nghi_qua_nhieu'  => [
                'nguong'  => $NGUONG_NGHI,
                'tong'    => $nghiQuaNhieu->count(),
                'data'    => $nghiQuaNhieu,
            ],
            'chua_check_in'   => [
                'ngay'  => $today,
                'tong'  => $chuaCheckIn->count(),
                'data'  => $chuaCheckIn,
            ],
            'ot_bat_thuong'   => [
                'nguong' => $NGUONG_OT,
                'tong'   => $otBatThuong->count(),
                'data'   => $otBatThuong,
            ],
        ]);
    }

    // ════════════════════════════════════════════════════════════════════════
    // METHOD ALIASES FOR ROUTING (phongBan & chucVu)
    // ════════════════════════════════════════════════════════════════════════
    public function phongBan()
    {
        return $this->nhAnSuPhongBan();
    }

    public function chucVu()
    {
        return $this->nhAnSuChucVu();
    }

    // ════════════════════════════════════════════════════════════════════════
    // LOAD ALL — Gọi 1 lần để FE load dashboard
    // GET /api/admin/thong-ke/dashboard
    // ════════════════════════════════════════════════════════════════════════
    public function dashboard(Request $request)
    {
        $thang = $request->input('thang', now()->month);
        $nam   = $request->input('nam',   now()->year);

        // Gọi nội bộ các hàm thống kê
        $req = new Request(['thang' => $thang, 'nam' => $nam, 'loai' => 'thang']);

        return response()->json([
            'status'      => true,
            'cham_cong'   => $this->chamCong($req)->getData(true),
            'nghi_phep'   => $this->nghiPhep($req)->getData(true),
            'tong_quan'   => $this->tongQuan()->getData(true),
            'phong_ban'   => $this->nhAnSuPhongBan()->getData(true),
            'chuc_vu'     => $this->nhAnSuChucVu()->getData(true),
            'canh_bao'    => $this->canhBao($req)->getData(true),
        ]);
    }
}
