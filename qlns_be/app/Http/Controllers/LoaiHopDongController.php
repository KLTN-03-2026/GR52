<?php

namespace App\Http\Controllers;

use App\Models\LoaiHopDong;
use Illuminate\Http\Request;

class LoaiHopDongController extends Controller
{
    public function index()
    {
        return response()->json(LoaiHopDong::where('tinh_trang', 1)->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ten_hop_dong' => 'required|string|max:100',
            'noi_dung'     => 'nullable|string',
            'tinh_trang'   => 'integer|in:0,1',
        ]);
        return response()->json(LoaiHopDong::create($validated), 201);
    }

    public function show(LoaiHopDong $loaiHopDong)
    {
        return response()->json($loaiHopDong);
    }

    public function update(Request $request, LoaiHopDong $loaiHopDong)
    {
        $loaiHopDong->update($request->validate([
            'ten_hop_dong' => 'string|max:100',
            'noi_dung'     => 'nullable|string',
            'tinh_trang'   => 'integer|in:0,1',
        ]));
        return response()->json($loaiHopDong);
    }

    public function destroy(LoaiHopDong $loaiHopDong)
    {
        $loaiHopDong->delete();
        return response()->json(['message' => 'Đã xóa loại hợp đồng']);
    }
}
