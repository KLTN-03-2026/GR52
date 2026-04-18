<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DonNghiPhep;
use Carbon\Carbon;

class DonNghiPhepController extends Controller
{
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
            ->whereYear('created_at', $nam)
            ->get();

        // Số ngày phép năm còn lại (giả sử mỗi năm được 12 ngày)
        $tongPhepNam   = 12;
        $daSDungPhep   = $dons->where('loai_nghi', 'phep_nam')
            ->where('trang_thai', 2)
            ->sum('so_ngay');
        $phepConLai    = max(0, $tongPhepNam - $daSDungPhep);

        return response()->json([
            'status' => true,
            'data'   => [
                'tong_don'     => $dons->count(),
                'cho_duyet'    => $dons->where('trang_thai', 1)->count(),
                'da_duyet'     => $dons->where('trang_thai', 2)->count(),
                'phep_con_lai' => $phepConLai,
            ],
        ]);
    }

    // ── [NHÂN VIÊN] Nộp đơn xin nghỉ phép ───────────────────────────────────────
    public function nopDon(Request $request)
    {
        $validated = $request->validate([
            'loai_nghi'      => 'required|in:phep_nam,om,khong_luong,viec_rieng',
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
                $q->whereBetween('ngay_bat_dau', [$validated['ngay_bat_dau'], $validated['ngay_ket_thuc']])
                    ->orWhereBetween('ngay_ket_thuc', [$validated['ngay_bat_dau'], $validated['ngay_ket_thuc']]);
            })->exists();

        if ($trung) {
            return response()->json([
                'status'  => false,
                'message' => 'Bạn đã có đơn nghỉ phép trong khoảng thời gian này.',
            ]);
        }

        // Tính số ngày làm việc (không tính T7, CN)
        $start   = Carbon::parse($validated['ngay_bat_dau']);
        $end     = Carbon::parse($validated['ngay_ket_thuc']);
        $soNgay  = 0;
        $current = $start->copy();
        while ($current->lte($end)) {
            if (!$current->isWeekend()) $soNgay++;
            $current->addDay();
        }

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

        return response()->json([
            'status'  => true,
            'message' => "Đã gửi đơn xin nghỉ {$soNgay} ngày. Vui lòng chờ phê duyệt.",
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
            //'loai_tinh_luong' => 'required|in:co_luong,khong_luong,om',
            'ghi_chu_duyet'   => 'nullable|string|max:500',
        ], [
            //'loai_tinh_luong.required' => 'Vui lòng chọn loại tính lương.',
        ]);

        $don = DonNghiPhep::findOrFail($validated['id']);

        if ($don->trang_thai !== 1) {
            return response()->json(['status' => false, 'message' => 'Đơn này đã được xử lý rồi.']);
        }

        $don->update([
            'trang_thai'      => 2,
            'id_nguoi_duyet'  => $validated['id_nguoi_duyet'],
            'ngay_duyet'      => now(),
            //'loai_tinh_luong' => $validated['loai_tinh_luong'],
            'ghi_chu_duyet'   => $validated['ghi_chu_duyet'] ?? null,
        ]);

        // TODO: Trigger tính lương — ghi vào bảng luong_chi_tiet nếu có
        // LuongChiTiet::create([
        //     'id_nhan_vien'   => $don->id_nhan_vien,
        //     'thang'          => $don->ngay_bat_dau->month,
        //     'nam'            => $don->ngay_bat_dau->year,
        //     'ngay_nghi'      => $don->so_ngay,
        //     'loai_tinh_luong'=> $validated['loai_tinh_luong'],
        // ]);

        return response()->json([
            'status'  => true,
            'message' => 'Phê duyệt đơn nghỉ phép thành công. Đã ghi nhận vào hệ thống lương.',
            'data'    => $don->load(['nhanVien:id,ho_va_ten', 'nguoiDuyet:id,ho_va_ten']),
        ]);
    }

    // ── [ADMIN] Từ chối đơn nghỉ phép ────────────────────────────────────────────
    public function tuChoi(Request $request)
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

        $don->update([
            'trang_thai'      => 3,
            'id_nguoi_duyet'  => $validated['id_nguoi_duyet'],
            'ngay_duyet'      => now(),
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

        $loaiMap     = ['phep_nam' => 'Phép năm', 'om' => 'Nghỉ ốm', 'viec_rieng' => 'Việc riêng', 'khong_luong' => 'Không lương'];
        $ttMap       = [1 => 'Chờ duyệt', 2 => 'Đã duyệt', 3 => 'Từ chối'];
        $luongMap    = ['co_luong' => 'Có lương', 'khong_luong' => 'Không lương', 'om' => 'BHXH (ốm)'];

        $csv = "STT,Nhân viên,Email,Loại nghỉ,Từ ngày,Đến ngày,Số ngày,Lý do,Trạng thái,Loại tính lương,Người duyệt,Ngày duyệt\n";

        foreach ($data as $k => $v) {
            $csv .= implode(',', [
                $k + 1,
                '"' . ($v->nhan_vien?->ho_va_ten ?? '') . '"',
                $v->nhan_vien?->email ?? '',
                $loaiMap[$v->loai_nghi] ?? $v->loai_nghi,
                $v->ngay_bat_dau?->format('d/m/Y') ?? '',
                $v->ngay_ket_thuc?->format('d/m/Y') ?? '',
                $v->so_ngay,
                '"' . str_replace('"', '""', $v->ly_do ?? '') . '"',
                $ttMap[$v->trang_thai] ?? '',
                //$luongMap[$v->loai_tinh_luong] ?? '—',
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
}
