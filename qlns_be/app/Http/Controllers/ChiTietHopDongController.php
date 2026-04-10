<?php

namespace App\Http\Controllers;

use App\Models\ChiTietHopDong;
use Illuminate\Http\Request;

class ChiTietHopDongController extends Controller
{
    public function index(Request $request)
    {
        $query = ChiTietHopDong::with(['nhanVien', 'loaiHopDong']);
        if ($request->filled('nhan_vien')) {
            $query->where('id_nhan_vien', $request->nhan_vien);
        }
        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_nhan_vien'     => 'required|exists:nhan_viens,id',
            'id_loai_hop_dong' => 'required|exists:loai_hop_dongs,id',
            'noi_dung'         => 'nullable|string',
            'ngay_ky'          => 'required|date',
            'ngay_bat_dau'     => 'required|date',
            'ngay_ket_thuc'    => 'nullable|date|after:ngay_bat_dau',
        ]);
        return response()->json(ChiTietHopDong::create($validated), 201);
    }

    public function show(ChiTietHopDong $chiTietHopDong)
    {
        return response()->json($chiTietHopDong->load(['nhanVien', 'loaiHopDong']));
    }

    public function update(Request $request, ChiTietHopDong $chiTietHopDong)
    {
        $chiTietHopDong->update($request->validate([
            'noi_dung'      => 'nullable|string',
            'ngay_ket_thuc' => 'nullable|date',
        ]));
        return response()->json($chiTietHopDong);
    }

    public function destroy(ChiTietHopDong $chiTietHopDong)
    {
        $chiTietHopDong->delete();
        return response()->json(['message' => 'Đã xóa hợp đồng']);
    }
}

