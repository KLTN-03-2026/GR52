<?php

namespace App\Http\Controllers;

use App\Models\ThuongVaPhat;
use Illuminate\Http\Request;

class ThuongVaPhatController extends Controller
{
    public function getData(Request $request)
    {
        $v = $request->validate(['thang' => 'required|integer', 'nam' => 'required|integer']);
        $data = ThuongVaPhat::with('nhanVien:id,ho_va_ten')
            ->where('thang', $v['thang'])->where('nam', $v['nam'])
            ->when($request->filled('loai'), fn($q) => $q->where('loai', $request->loai))
            ->when($request->filled('id_nhan_vien'), fn($q) => $q->where('id_nhan_vien', $request->id_nhan_vien))
            ->orderBy('created_at', 'desc')->get();

        return response()->json(['status' => true, 'data' => $data]);
    }

    public function create(Request $request)
    {
        $v = $request->validate([
            'id_nhan_vien' => 'required|exists:nhan_viens,id',
            'thang'        => 'required|integer|between:1,12',
            'nam'          => 'required|integer|min:2020',
            'loai'         => 'required|in:thuong,phat',
            'so_tien'      => 'required|numeric|min:1000',
            'ly_do'        => 'nullable|string|max:500',
        ], [
            'so_tien.min' => 'Số tiền tối thiểu 1,000 VNĐ.',
        ]);

        $tp = ThuongVaPhat::create(array_merge($v, ['id_nguoi_tao' => $request->user()->id ?? null]));

        return response()->json([
            'status'  => true,
            'message' => ($v['loai'] === 'thuong' ? 'Thêm thưởng' : 'Thêm phạt') . ' thành công.',
            'data'    => $tp->load('nhanVien:id,ho_va_ten'),
        ], 201);
    }

    public function update(Request $request)
    {
        $tp = ThuongVaPhat::findOrFail($request->id);
        $v  = $request->validate([
            'loai'    => 'in:thuong,phat',
            'so_tien' => 'numeric|min:1000',
            'ly_do'   => 'nullable|string|max:500',
        ]);
        $tp->update($v);
        return response()->json(['status' => true, 'message' => 'Cập nhật thành công.', 'data' => $tp]);
    }

    public function delete(Request $request)
    {
        ThuongVaPhat::findOrFail($request->id)->delete();
        return response()->json(['status' => true, 'message' => 'Đã xóa bản ghi.']);
    }

    // ── NHÂN VIÊN XEM THƯỞNG VÀ PHẠT CỦA MÌNH ────────────────────────
    /**
     * Nhân viên xem thưởng và phạt của bản thân
     */
    public function viewMyThuongVaPhat(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'Không tìm thấy thông tin người dùng.'], 401);
        }

        $v = $request->validate([
            'thang' => 'nullable|integer|between:1,12',
            'nam'   => 'nullable|integer|min:2020',
            'loai'  => 'nullable|in:thuong,phat',
        ]);

        $query = ThuongVaPhat::where('id_nhan_vien', $user->id)
            ->with(['nguoiTao:id,ho_va_ten']);

        // Nếu có chỉ định tháng/năm
        if (isset($v['thang'])) {
            $query->where('thang', $v['thang']);
        }
        if (isset($v['nam'])) {
            $query->where('nam', $v['nam']);
        }

        // Nếu có lọc theo loại
        if (isset($v['loai'])) {
            $query->where('loai', $v['loai']);
        }

        $data = $query->orderByDesc('nam')->orderByDesc('thang')->get();

        // Tính tổng thưởng và phạt
        $tongThuong = $data->where('loai', 'thuong')->sum('so_tien');
        $tongPhat   = $data->where('loai', 'phat')->sum('so_tien');

        return response()->json([
            'status'      => true,
            'data'        => $data,
            'tong_thuong' => $tongThuong,
            'tong_phat'   => $tongPhat,
            'net'         => $tongThuong - $tongPhat,
        ]);
    }
}
