<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DonNghiPhep;
use Carbon\Carbon;

class DonNghiPhepController extends Controller
{
    private const PHEP_NAM_MOI_NAM = 12;
    private const NGHI_OM_MOI_NAM = 5;
    private const LOAI_NGHI_HOP_LE = ['phep_nam', 'om'];

    public function danhSachNhanVien(Request $request)
    {
        $nhanVien = $request->user();
        $nam = now()->year;

        $data = DonNghiPhep::with(['nguoiDuyet:id,ho_va_ten'])
            ->where('id_nhan_vien', $nhanVien->id)
            ->whereYear('created_at', $nam)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data'   => $data,
        ]);
    }

    // ── [NHÂN VIÊN] Thống kê phép của chính mình ─────────────────────────────────
    public function thongKeNhanVien(Request $request)
    {
        $nhanVien = $request->user();
        $nam      = now()->year;

        $dons = DonNghiPhep::where('id_nhan_vien', $nhanVien->id)
            ->whereYear('ngay_bat_dau', $nam)
            ->get();

        $phepNam = $this->tinhHanMucConLai($nhanVien->id, 'phep_nam', $nam);
        $nghiOm  = $this->tinhHanMucConLai($nhanVien->id, 'om', $nam);

        return response()->json([
            'status' => true,
            'data'   => [
                'tong_don'           => $dons->count(),
                'cho_duyet'          => $dons->where('trang_thai', 1)->count(),
                'da_duyet'           => $dons->where('trang_thai', 2)->count(),
                'phep_con_lai'       => $phepNam['con_lai'],
                'tong_phep_nam'      => $phepNam['tong_han_muc'],
                'phep_nam_da_dung'   => $phepNam['da_dung'],
                'nghi_om_con_lai'    => $nghiOm['con_lai'],
                'tong_nghi_om'       => $nghiOm['tong_han_muc'],
                'nghi_om_da_dung'    => $nghiOm['da_dung'],
            ],
        ]);
    }

    // ── [NHÂN VIÊN] Nộp đơn xin nghỉ phép ───────────────────────────────────────
    public function nopDon(Request $request)
    {
        $validated = $request->validate([
            'loai_nghi'      => 'required|in:phep_nam,om',
            'ngay_bat_dau'   => 'required|date|after_or_equal:today',
            'ngay_ket_thuc'  => 'required|date|after_or_equal:ngay_bat_dau',
            'ly_do'          => 'required|string|max:500',
            'nguoi_thay_the' => 'nullable|string|max:100',
        ], [
            'loai_nghi.required'          => 'Vui lòng chọn loại nghỉ phép.',
            'ngay_bat_dau.after_or_equal' => 'Ngày bắt đầu phải từ hôm nay trở đi.',
            'ngay_ket_thuc.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
            'ly_do.required'              => 'Vui lòng nhập lý do nghỉ phép.',
        ]);

        $nhanVien = $request->user();

        // Kiểm tra trùng đơn đang chờ hoặc đã duyệt
        $trung = DonNghiPhep::where('id_nhan_vien', $nhanVien->id)
            ->whereIn('trang_thai', [1, 2])
            ->where(function ($q) use ($validated) {
                $q->where('ngay_bat_dau', '<=', $validated['ngay_ket_thuc'])
                    ->where('ngay_ket_thuc', '>=', $validated['ngay_bat_dau']);
            })->exists();

        if ($trung) {
            return response()->json([
                'status'  => false,
                'message' => 'Bạn đã có đơn nghỉ phép trong khoảng thời gian này.',
            ]);
        }

        $soNgay = $this->tinhSoNgayLamViec($validated['ngay_bat_dau'], $validated['ngay_ket_thuc']);

        $don = DonNghiPhep::create([
            'id_nhan_vien'   => $nhanVien->id,
            'loai_nghi'      => $validated['loai_nghi'],
            'ngay_bat_dau'   => $validated['ngay_bat_dau'],
            'ngay_ket_thuc'  => $validated['ngay_ket_thuc'],
            'so_ngay'        => $soNgay,
            'ly_do'          => $validated['ly_do'],
            'nguoi_thay_the' => $validated['nguoi_thay_the'] ?? null,
            'trang_thai'     => 1,
        ]);

        $hanMuc = $this->tinhHanMucConLai($nhanVien->id, $validated['loai_nghi'], Carbon::parse($validated['ngay_bat_dau'])->year);
        $messageThem = $soNgay > $hanMuc['con_lai']
            ? ' Phần vượt hạn mức sẽ bị trừ lương nếu được duyệt.'
            : '';

        return response()->json([
            'status'  => true,
            'message' => "Đã gửi đơn xin nghỉ {$soNgay} ngày. Vui lòng chờ phê duyệt.{$messageThem}",
            'data'    => $don,
        ], 201);
    }

    // ── [NHÂN VIÊN] Hủy đơn (chỉ khi chưa duyệt) ────────────────────────────────
    public function huyDon(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:don_nghi_pheps,id',
        ]);

        $nhanVien = $request->user();
        $don      = DonNghiPhep::findOrFail($validated['id']);

        // Bảo mật: chỉ hủy đơn của chính mình
        if ($don->id_nhan_vien !== $nhanVien->id) {
            return response()->json(['status' => false, 'message' => 'Không có quyền thực hiện.'], 403);
        }

        if ($don->trang_thai !== 1) {
            return response()->json([
                'status'  => false,
                'message' => 'Chỉ có thể hủy đơn đang chờ duyệt.',
            ]);
        }

        $don->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Đã hủy đơn nghỉ phép thành công.',
        ]);
    }

    // ── [ADMIN] Duyệt đơn — CẬP NHẬT để lưu thông tin lương ─────────────────────
    public function duyet(Request $request)
    {
        $validated = $request->validate([
            'id'              => 'required|exists:don_nghi_pheps,id',
            'id_nguoi_duyet'  => 'required|exists:nhan_viens,id',
            'ghi_chu_duyet'   => 'nullable|string|max:500',
        ]);

        $don = DonNghiPhep::findOrFail($validated['id']);

        if ($don->trang_thai !== 1) {
            return response()->json(['status' => false, 'message' => 'Đơn này đã được xử lý rồi.']);
        }

        $tachNgayLuong = $this->tinhNgayLuongChoDon($don);

        $don->update([
            'trang_thai'      => 2,
            'id_nguoi_duyet'  => $validated['id_nguoi_duyet'],
            'ngay_duyet'      => now(),
            'so_ngay_co_luong' => $tachNgayLuong['co_luong'],
            'so_ngay_khong_luong' => $tachNgayLuong['khong_luong'],
            'ghi_chu_duyet'   => $validated['ghi_chu_duyet'] ?? null,
        ]);

        return response()->json([
            'status'  => true,
            'message' => "Phê duyệt thành công. Có lương {$tachNgayLuong['co_luong']} ngày, trừ lương {$tachNgayLuong['khong_luong']} ngày.",
            'data'    => $don->load(['nhanVien:id,ho_va_ten', 'nguoiDuyet:id,ho_va_ten']),
        ]);
    }

    // ── [ADMIN] Từ chối đơn nghỉ phép ────────────────────────────────────────────
    public function tuChoi(Request $request)
    {
        $validated = $request->validate([
            'id'              => 'required|exists:don_nghi_pheps,id',
            'id_nguoi_duyet'  => 'required|exists:nhan_viens,id',
            'ly_do_tu_choi'   => 'required|string|max:500',
            'ghi_chu_duyet'   => 'nullable|string|max:500',
        ]);

        $don = DonNghiPhep::findOrFail($validated['id']);

        if ($don->trang_thai !== 1) {
            return response()->json(['status' => false, 'message' => 'Đơn này đã được xử lý rồi.']);
        }

        $don->update([
            'trang_thai'      => 3,
            'id_nguoi_duyet'  => $validated['id_nguoi_duyet'],
            'ngay_duyet'      => now(),
            'ly_do_tu_choi'   => $validated['ly_do_tu_choi'],
            'ghi_chu_duyet'   => $validated['ghi_chu_duyet'] ?? null,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Đã từ chối đơn nghỉ phép thành công.',
            'data'    => $don->load(['nhanVien:id,ho_va_ten', 'nguoiDuyet:id,ho_va_ten']),
        ]);
    }

    // ── [ADMIN] Xóa đơn nghỉ phép ────────────────────────────────────────────────
    public function delete(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:don_nghi_pheps,id',
        ]);

        $don = DonNghiPhep::findOrFail($validated['id']);
        $don->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Đã xóa đơn nghỉ phép thành công.',
        ]);
    }

    // ── [ADMIN] Lấy danh sách đơn nghỉ phép theo tháng/năm ──────────────────────
    public function getData(Request $request)
    {
        $validated = $request->validate([
            'thang' => 'required|integer|between:1,12',
            'nam'   => 'required|integer|min:2020',
        ]);

        $data = DonNghiPhep::with(['nhanVien:id,ho_va_ten,email', 'nguoiDuyet:id,ho_va_ten'])
            ->whereMonth('ngay_bat_dau', $validated['thang'])
            ->whereYear('ngay_bat_dau',  $validated['nam'])
            ->orderBy('trang_thai')
            ->orderBy('created_at')
            ->get();

        return response()->json([
            'status' => true,
            'data'   => $data,
        ]);
    }

    // ── [ADMIN] Xuất báo cáo Excel/CSV ───────────────────────────────────────────
    public function xuatExcel(Request $request)
    {
        $validated = $request->validate([
            'thang' => 'required|integer|between:1,12',
            'nam'   => 'required|integer|min:2020',
        ]);

        $data = DonNghiPhep::with(['nhanVien:id,ho_va_ten,email', 'nguoiDuyet:id,ho_va_ten'])
            ->whereMonth('ngay_bat_dau', $validated['thang'])
            ->whereYear('ngay_bat_dau',  $validated['nam'])
            ->orderBy('trang_thai')
            ->orderBy('created_at')
            ->get();

        $loaiMap     = ['phep_nam' => 'Phép năm', 'om' => 'Nghỉ ốm'];
        $ttMap       = [1 => 'Chờ duyệt', 2 => 'Đã duyệt', 3 => 'Từ chối'];

        $csv = "\xEF\xBB\xBF";
        $csv .= "STT,Nhân viên,Email,Loại nghỉ,Từ ngày,Đến ngày,Số ngày,Có lương,Trừ lương,Lý do,Trạng thái,Người duyệt,Ngày duyệt\n";

        foreach ($data as $k => $v) {
            $csv .= implode(',', [
                $k + 1,
                '"' . ($v->nhan_vien?->ho_va_ten ?? '') . '"',
                $v->nhan_vien?->email ?? '',
                $loaiMap[$v->loai_nghi] ?? $v->loai_nghi,
                $v->ngay_bat_dau?->format('d/m/Y') ?? '',
                $v->ngay_ket_thuc?->format('d/m/Y') ?? '',
                $v->so_ngay,
                $this->laySoNgayCoLuong($v),
                $this->laySoNgayKhongLuong($v),
                '"' . str_replace('"', '""', $v->ly_do ?? '') . '"',
                $ttMap[$v->trang_thai] ?? '',
                '"' . ($v->nguoi_duyet?->ho_va_ten ?? '') . '"',
                $v->ngay_duyet?->format('d/m/Y') ?? '',
            ]) . "\n";
        }

        $filename = "bao_cao_nghi_phep_T{$validated['thang']}_{$validated['nam']}.csv";

        return response($csv, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    private function tinhSoNgayLamViec(string $ngayBatDau, string $ngayKetThuc): int
    {
        $start   = Carbon::parse($ngayBatDau);
        $end     = Carbon::parse($ngayKetThuc);
        $soNgay  = 0;
        $current = $start->copy();

        while ($current->lte($end)) {
            if (!$current->isWeekend()) {
                $soNgay++;
            }
            $current->addDay();
        }

        return $soNgay;
    }

    private function tinhHanMucConLai(int $idNhanVien, string $loaiNghi, int $nam, ?int $excludeId = null): array
    {
        if (!in_array($loaiNghi, self::LOAI_NGHI_HOP_LE, true)) {
            return ['tong_han_muc' => 0, 'da_dung' => 0, 'con_lai' => 0];
        }

        if ($loaiNghi === 'om') {
            $tongHanMuc = self::NGHI_OM_MOI_NAM;
        } else {
            $daDungNamTruoc = $this->tongNgayCoLuongDaDuyet($idNhanVien, 'phep_nam', $nam - 1);
            $duNamTruoc = max(0, self::PHEP_NAM_MOI_NAM - $daDungNamTruoc);
            $tongHanMuc = self::PHEP_NAM_MOI_NAM + min(self::PHEP_NAM_MOI_NAM, $duNamTruoc);
        }

        $daDung = $this->tongNgayCoLuongDaDuyet($idNhanVien, $loaiNghi, $nam, $excludeId);

        return [
            'tong_han_muc' => $tongHanMuc,
            'da_dung'      => $daDung,
            'con_lai'      => max(0, $tongHanMuc - $daDung),
        ];
    }

    private function tinhNgayLuongChoDon(DonNghiPhep $don): array
    {
        $hanMuc = $this->tinhHanMucConLai(
            $don->id_nhan_vien,
            $don->loai_nghi,
            $don->ngay_bat_dau->year,
            $don->id
        );

        $coLuong = min($don->so_ngay, $hanMuc['con_lai']);

        return [
            'co_luong' => $coLuong,
            'khong_luong' => max(0, $don->so_ngay - $coLuong),
        ];
    }

    private function tongNgayCoLuongDaDuyet(int $idNhanVien, string $loaiNghi, int $nam, ?int $excludeId = null): int
    {
        return DonNghiPhep::where('id_nhan_vien', $idNhanVien)
            ->where('loai_nghi', $loaiNghi)
            ->where('trang_thai', 2)
            ->whereYear('ngay_bat_dau', $nam)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->get()
            ->sum(fn($don) => $this->laySoNgayCoLuong($don));
    }

    private function laySoNgayCoLuong(DonNghiPhep $don): int
    {
        if ($don->so_ngay_co_luong !== null) {
            return (int) $don->so_ngay_co_luong;
        }

        return in_array($don->loai_nghi, self::LOAI_NGHI_HOP_LE, true) ? (int) $don->so_ngay : 0;
    }

    private function laySoNgayKhongLuong(DonNghiPhep $don): int
    {
        if ($don->so_ngay_khong_luong !== null) {
            return (int) $don->so_ngay_khong_luong;
        }

        return in_array($don->loai_nghi, self::LOAI_NGHI_HOP_LE, true) ? 0 : (int) $don->so_ngay;
    }
}
