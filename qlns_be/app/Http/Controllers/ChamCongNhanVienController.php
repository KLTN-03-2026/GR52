<?php

namespace App\Http\Controllers;

use App\Models\ChamCong;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ChamCongNhanVienController extends Controller
{
    // Helper lấy user
    private function getUser()
    {
        $nhanVien = Auth::guard('nhanvien')->user();

        if (!$nhanVien) {
            abort(response()->json([
                'status' => false,
                'message' => 'Bạn chưa đăng nhập'
            ], 401));
        }

        return $nhanVien;
    }

    // ─────────────────────────────
    public function homNay()
    {
        $nhanVien = $this->getUser();
        $today = Carbon::today()->toDateString();

        $records = ChamCong::where('id_nhan_vien', $nhanVien->id)
            ->whereDate('ngay_lam_viec', $today)
            ->get();

        return response()->json([
            'status' => true,
            'data' => $records
        ]);
    }

    // ─────────────────────────────
    public function lichSu(Request $request)
    {
        $nhanVien = $this->getUser();

        $validated = $request->validate([
            'thang' => 'required|integer|between:1,12',
            'nam'   => 'required|integer|min:2024',
        ]);

        $data = ChamCong::where('id_nhan_vien', $nhanVien->id)
            ->whereMonth('ngay_lam_viec', $validated['thang'])
            ->whereYear('ngay_lam_viec', $validated['nam'])
            ->orderBy('ngay_lam_viec', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    // ─────────────────────────────
    public function checkIn(Request $request)
    {
        $nhanVien = $this->getUser();

        $validated = $request->validate([
            'ca_lam' => 'required|in:sang,chieu,toi',
            'anh_check_in' => 'required|string',
        ]);

        $today = Carbon::today()->toDateString();

        $exists = ChamCong::where('id_nhan_vien', $nhanVien->id)
            ->whereDate('ngay_lam_viec', $today)
            ->where('ca_lam', $validated['ca_lam'])
            ->first();

        if ($exists) {
            return response()->json([
                'status' => false,
                'message' => 'Đã check-in ca này'
            ]);
        }

        $anhPath = $this->saveBase64Image($validated['anh_check_in'], 'checkin');

        $chamCong = ChamCong::create([
            'id_nhan_vien' => $nhanVien->id,
            'ngay_lam_viec' => $today,
            'ca_lam' => $validated['ca_lam'],
            'gio_vao' => Carbon::now()->format('H:i:s'),
            'anh_check_in' => $anhPath,
            'trang_thai' => 0,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Check-in thành công',
            'data' => $chamCong
        ]);
    }

    // ─────────────────────────────
    public function checkOut(Request $request)
    {
        $nhanVien = $this->getUser();

        $validated = $request->validate([
            'id' => 'required|exists:cham_congs,id',
            'anh_check_out' => 'required|string',
        ]);

        $chamCong = ChamCong::where('id', $validated['id'])
            ->where('id_nhan_vien', $nhanVien->id)
            ->first();

        if (!$chamCong) {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy dữ liệu'
            ]);
        }

        if ($chamCong->trang_thai >= 1) {
            return response()->json([
                'status' => false,
                'message' => 'Đã check-out rồi'
            ]);
        }

        $date = Carbon::parse($chamCong->ngay_lam_viec)->format('Y-m-d');
        $gioVao = Carbon::createFromFormat('Y-m-d H:i:s', $date . ' ' . $chamCong->gio_vao);
        $gioRa = Carbon::now();

        $soGioLam = round($gioRa->diffInMinutes($gioVao) / 60, 2);

        $anhPath = $this->saveBase64Image($validated['anh_check_out'], 'checkout');

        $chamCong->update([
            'gio_ra' => $gioRa->format('H:i:s'),
            'so_gio_lam' => $soGioLam,
            'anh_check_out' => $anhPath,
            'trang_thai' => 1,
        ]);

        return response()->json([
            'status' => true,
            'message' => "Check-out thành công ($soGioLam giờ)",
            'data' => $chamCong
        ]);
    }

    // ─────────────────────────────
    private function saveBase64Image($base64, $prefix)
    {
        preg_match('/^data:image\/(\w+);base64,/', $base64, $matches);
        $ext = $matches[1] ?? 'jpg';

        $data = preg_replace('/^data:image\/\w+;base64,/', '', $base64);

        $fileName = $prefix . '_' . time() . '.' . $ext;
        $path = 'cham_cong/' . $fileName;

        Storage::disk('public')->put($path, base64_decode($data));

        return $path;
    }
}
