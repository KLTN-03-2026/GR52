<?php

namespace App\Http\Controllers;

use App\Models\ViTriTuyenDung;
use Illuminate\Http\Request;

class ViTriTuyenDungController extends Controller
{
    public function index(Request $request)
    {
        $query = ViTriTuyenDung::with(['phongBan', 'chucVu']);
        if ($request->filled('tinh_trang')) {
            $query->where('tinh_trang', $request->tinh_trang);
        }
        return response()->json($query->paginate(10));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_phong_ban'  => 'nullable|exists:phong_bans,id',
            'id_chuc_vu'    => 'nullable|exists:chuc_vus,id',
            'tieu_de'       => 'nullable|string|max:100',
            'mo_ta'         => 'nullable|string',
            'so_luong'      => 'nullable|integer|min:1',
            'ngay_bat_dau'  => 'nullable|date',
            'ngay_ket_thuc' => 'nullable|date|after:ngay_bat_dau',
            'tinh_trang'    => 'integer|in:0,1',
        ]);
        return response()->json(ViTriTuyenDung::create($validated), 201);
    }

    public function show(ViTriTuyenDung $viTriTuyenDung)
    {
        return response()->json($viTriTuyenDung->load(['phongBan', 'chucVu']));
    }

    public function getData(Request $request)
    {
        $query = ViTriTuyenDung::with(['phongBan', 'chucVu']);

        if ($request->filled('phong_ban')) {
            $query->where('id_phong_ban', $request->phong_ban);
        }
        if ($request->filled('id_chuc_vu')) {
            $query->where('id_chuc_vu', $request->id_chuc_vu);
        }
        if ($request->filled('search')) {
            $query->where('tieu_de', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('per_page')) {
            return response()->json($query->paginate((int) $request->per_page));
        }

        return response()->json($query->get());
    }

    public function getDataOpen(Request $request)
    {
        $query = ViTriTuyenDung::with(['phongBan', 'chucVu'])->where('tinh_trang', 1);

        if ($request->filled('search')) {
            $query->where('tieu_de', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('per_page')) {
            return response()->json($query->paginate((int) $request->per_page));
        }

        return response()->json($query->select('id', 'tieu_de', 'so_luong', 'ngay_bat_dau', 'ngay_ket_thuc', 'id_phong_ban', 'id_chuc_vu')->get());
    }

    public function update(Request $request)
    {
        $viTriTuyenDung = ViTriTuyenDung::find($request->id);

        if (! $viTriTuyenDung) {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy vị trí tuyển dụng'
            ], 404);
        }

        $validated = $request->validate([
            'id_phong_ban'  => 'nullable|exists:phong_bans,id',
            'id_chuc_vu'    => 'nullable|exists:chuc_vus,id',
            'tieu_de'       => 'nullable|string|max:100',
            'mo_ta'         => 'nullable|string',
            'so_luong'      => 'nullable|integer|min:1',
            'ngay_bat_dau'  => 'nullable|date',
            'ngay_ket_thuc' => 'nullable|date|after:ngay_bat_dau',
            'tinh_trang'    => 'integer|in:0,1',
        ]);

        $viTriTuyenDung->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Cập nhật vị trí thành công',
            'data' => $viTriTuyenDung,
        ]);
    }

    public function destroy(ViTriTuyenDung $viTriTuyenDung)
    {
        $viTriTuyenDung->delete();
        return response()->json(['message' => 'Đã xóa vị trí tuyển dụng']);
    }
}
