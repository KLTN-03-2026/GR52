<?php

namespace App\Http\Controllers;

use App\Models\ChamCong;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ChamCongAdminController extends Controller
{
    // ── DANH SÁCH CHẤM CÔNG THEO NGÀY ────────────────────────────────────────
    // GET /api/admin/cham-cong/data
    public function getData(Request $request)
    {
        $ngay = $request->filled('ngay')
            ? $request->ngay
            : Carbon::today()->toDateString();

        $query = ChamCong::with('nhanVien:id,ho_va_ten,email,id_phong_ban')
            ->whereDate('ngay_lam_viec', $ngay);

        if ($request->filled('ca_lam'))       $query->where('ca_lam', $request->ca_lam);
        if ($request->filled('trang_thai'))    $query->where('trang_thai', $request->trang_thai);
        if ($request->filled('id_nhan_vien'))  $query->where('id_nhan_vien', $request->id_nhan_vien);

        $data = $query->orderBy('gio_vao', 'asc')->get();

        // Thống kê nhanh trong ngày
        $tongCheckin  = $data->count();
        $daCheckout   = $data->where('trang_thai', '>=', 1)->count();
        $chuaCheckout = $data->where('trang_thai', 0)->count();
        $nghiNgo      = $data->where('trang_thai', 3)->count();
        $daxacNhan    = $data->where('trang_thai', 2)->count();

        return response()->json([
            'status' => true,
            'ngay'   => $ngay,
            'data'   => $data,
            'thong_ke' => [
                'tong_checkin'  => $tongCheckin,
                'da_checkout'   => $daCheckout,
                'chua_checkout' => $chuaCheckout,
                'nghi_ngo'      => $nghiNgo,
                'da_xac_nhan'   => $daxacNhan,
            ],
        ]);
    }

    // ── XEM ẢNH CHECK-IN / CHECK-OUT CỦA 1 BẢN GHI ──────────────────────────
    // GET /api/admin/cham-cong/xem-anh/{id}
    public function xemAnh($id)
    {
        $chamCong = ChamCong::with('nhanVien:id,ho_va_ten')->findOrFail($id);

        return response()->json([
            'status' => true,
            'data'   => [
                'id'            => $chamCong->id,
                'nhan_vien'     => $chamCong->nhanVien->ho_va_ten,
                'ngay'          => $chamCong->ngay_lam_viec->format('d/m/Y'),
                'ca_lam'        => $chamCong->ca_lam,
                'gio_vao'       => $chamCong->gio_vao,
                'gio_ra'        => $chamCong->gio_ra,
                'so_gio_lam'    => $chamCong->so_gio_lam,
                // Trả về URL đầy đủ để FE hiển thị ảnh
                'url_anh_checkin'  => $chamCong->anh_check_in
                    ? asset('storage/' . $chamCong->anh_check_in)
                    : null,
                'url_anh_checkout' => $chamCong->anh_check_out
                    ? asset('storage/' . $chamCong->anh_check_out)
                    : null,
                'trang_thai'    => $chamCong->trang_thai,
                'ghi_chu_nv'   => $chamCong->ghi_chu_nhan_vien,
                'ghi_chu_admin' => $chamCong->ghi_chu_admin,
            ],
        ]);
    }

    // ── ADMIN XÁC NHẬN (hợp lệ) ──────────────────────────────────────────────
    // POST /api/admin/cham-cong/xac-nhan
    public function xacNhan(Request $request)
    {
        $validated = $request->validate([
            'id'       => 'required|exists:cham_congs,id',
            'ghi_chu'  => 'nullable|string|max:255',
        ]);

        $chamCong = ChamCong::findOrFail($validated['id']);

        if ($chamCong->trang_thai === 2) {
            return response()->json(['status' => false, 'message' => 'Bản ghi đã được xác nhận rồi.']);
        }

        $chamCong->update([
            'trang_thai'    => 2,
            'ghi_chu_admin' => $validated['ghi_chu'] ?? 'Xác nhận hợp lệ',
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Đã xác nhận chấm công hợp lệ.',
            'data'    => $chamCong,
        ]);
    }

    // ── ADMIN ĐÁNH DẤU NGHI NGỜ ──────────────────────────────────────────────
    // POST /api/admin/cham-cong/nghi-ngo
    public function danhDauNghiNgo(Request $request)
    {
        $validated = $request->validate([
            'id'      => 'required|exists:cham_congs,id',
            'ghi_chu' => 'required|string|max:500',
        ], [
            'ghi_chu.required' => 'Vui lòng nhập lý do nghi ngờ.',
        ]);

        $chamCong = ChamCong::findOrFail($validated['id']);
        $chamCong->update([
            'trang_thai'    => 3,
            'ghi_chu_admin' => $validated['ghi_chu'],
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Đã đánh dấu nghi ngờ và ghi chú.',
            'data'    => $chamCong,
        ]);
    }

    // ── ADMIN XÁC NHẬN HÀNG LOẠT ─────────────────────────────────────────────
    // POST /api/admin/cham-cong/xac-nhan-hang-loat
    public function xacNhanHangLoat(Request $request)
    {
        $validated = $request->validate([
            'ids'     => 'required|array|min:1',
            'ids.*'   => 'exists:cham_congs,id',
        ]);

        $updated = ChamCong::whereIn('id', $validated['ids'])
            ->where('trang_thai', 1) // chỉ xác nhận những cái đã check-out
            ->update([
                'trang_thai'    => 2,
                'ghi_chu_admin' => 'Xác nhận hàng loạt',
            ]);

        return response()->json([
            'status'  => true,
            'message' => "Đã xác nhận {$updated} bản ghi chấm công.",
        ]);
    }

    // ── THỐNG KÊ THÁNG ───────────────────────────────────────────────────────
    // GET /api/admin/cham-cong/thong-ke
    public function thongKe(Request $request)
    {
        $validated = $request->validate([
            'thang' => 'required|integer|between:1,12',
            'nam'   => 'required|integer|min:2024',
        ]);

        $data = ChamCong::with('nhanVien:id,ho_va_ten')
            ->whereMonth('ngay_lam_viec', $validated['thang'])
            ->whereYear('ngay_lam_viec',  $validated['nam'])
            ->selectRaw('
                id_nhan_vien,
                COUNT(DISTINCT ngay_lam_viec) as so_ngay,
                COUNT(*) as so_luot,
                ROUND(SUM(so_gio_lam), 2) as tong_gio,
                COUNT(CASE WHEN trang_thai = 3 THEN 1 END) as so_nghi_ngo
            ')
            ->groupBy('id_nhan_vien')
            ->get();

        return response()->json(['status' => true, 'data' => $data]);
    }

    // ── DOWNLOAD ẢNH CHECK-IN ────────────────────────────────────────────────
    // GET /api/admin/cham-cong/download-anh-checkin/{id}
    public function downloadAnhCheckin($id)
    {
        $chamCong = ChamCong::findOrFail($id);

        if (!$chamCong->anh_check_in) {
            return response()->json(['message' => 'Chưa có ảnh check-in'], 404);
        }

        // Thử lấy từ storage disk public
        if (\Storage::disk('public')->exists($chamCong->anh_check_in)) {
            $filePath = \Storage::disk('public')->path($chamCong->anh_check_in);
            return response()->file($filePath);
        }

        // Fallback: thử từ storage/app/public (nếu symlink chưa tạo)
        $fallbackPath = storage_path('app/public/' . $chamCong->anh_check_in);
        if (file_exists($fallbackPath)) {
            return response()->file($fallbackPath);
        }

        \Log::error('Ảnh check-in không tồn tại: ' . $chamCong->anh_check_in);
        return response()->json(['message' => 'Tệp ảnh không tồn tại'], 404);
    }

    // ── DOWNLOAD ẢNH CHECK-OUT ───────────────────────────────────────────────
    // GET /api/admin/cham-cong/download-anh-checkout/{id}
    public function downloadAnhCheckout($id)
    {
        $chamCong = ChamCong::findOrFail($id);

        if (!$chamCong->anh_check_out) {
            return response()->json(['message' => 'Chưa có ảnh check-out'], 404);
        }

        // Thử lấy từ storage disk public
        if (\Storage::disk('public')->exists($chamCong->anh_check_out)) {
            $filePath = \Storage::disk('public')->path($chamCong->anh_check_out);
            return response()->file($filePath);
        }

        // Fallback: thử từ storage/app/public (nếu symlink chưa tạo)
        $fallbackPath = storage_path('app/public/' . $chamCong->anh_check_out);
        if (file_exists($fallbackPath)) {
            return response()->file($fallbackPath);
        }

        \Log::error('Ảnh check-out không tồn tại: ' . $chamCong->anh_check_out);
        return response()->json(['message' => 'Tệp ảnh không tồn tại'], 404);
    }
}
