<?php

namespace App\Http\Controllers;

use App\Models\PhongBan;
use App\Models\NhanVien;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExcelPhongBanExport;

class PhongBanController extends Controller
{
    public function getData(Request $request)
    {
        $query = PhongBan::with(['phongBanCha', 'truongPhong']);

        if ($request->filled('search')) {
            $query->where('ten_phong_ban', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('per_page')) {
            return response()->json($query->paginate((int) $request->per_page));
        }

        return response()->json($query->select('id', 'ten_phong_ban', 'id_phong_ban_cha', 'id_truong_phong', 'tinh_trang')->get());
    }



    public function index()
    {
        return response()->json(
            PhongBan::with(['phongBanCha', 'truongPhong'])->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ten_phong_ban'    => 'required|string|max:100',
            'id_phong_ban_cha' => 'nullable|exists:phong_bans,id',
            'id_truong_phong'  => 'nullable|exists:nhan_viens,id',
            'tinh_trang'       => 'integer|in:0,1',
        ]);
        $phongBan = PhongBan::create($validated);
        $phongBan->load('truongPhong');
        return response()->json([
            'status' => true,
            'message' => 'Đổi thêm mới thành công',
            'data' => $phongBan
        ]);
    }
    
    public function show(PhongBan $phongBan)
    {
        return response()->json(
            $phongBan->load(['phongBanCha', 'truongPhong', 'phongBanCon', 'nhanViens'])
        );
    }

    public function update(Request $request, PhongBan $phongBan)
    {
        $validated = $request->validate([
            'ten_phong_ban'    => 'string|max:100',
            'id_phong_ban_cha' => 'nullable|exists:phong_bans,id',
            'id_truong_phong'  => 'nullable|exists:nhan_viens,id',
            'tinh_trang'       => 'integer|in:0,1',
        ]);
        $phongBan->update($validated);
        $phongBan->load('truongPhong');
        return response()->json([
            'status' => true,
            'message' => 'Đổi cập nhật thành công',
            'data' => $phongBan
        ]);
    }

    public function destroy(PhongBan $phongBan)
    {
        $phongBan->delete();
        return response()->json(['message' => 'Đã xóa phòng ban']);
    }

    public function changeStatus(Request $request)
{
    $validated = $request->validate([
        'id'         => 'required|exists:phong_bans,id',
        'tinh_trang' => 'required|in:0,1',
    ]);


    $phongBan = PhongBan::findOrFail($validated['id']);
    $phongBan->update(['tinh_trang' => (int) $validated['tinh_trang']]);

    return response()->json([
        'status'  => true,
        'message' => 'Đổi trạng thái thành công',
        'data'    => $phongBan
    ]);
}
    public function updatePhongBan(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:phong_bans,id',
            'ten_phong_ban'    => 'string|max:100',
            'id_phong_ban_cha' => 'nullable|exists:phong_bans,id',
            'id_truong_phong'  => 'nullable|exists:nhan_viens,id',
            'tinh_trang'       => 'integer|in:0,1',
        ]);

        $phongBan = PhongBan::findOrFail($validated['id']);
        $phongBan->update(array_filter([
            'ten_phong_ban' => $validated['ten_phong_ban'] ?? null,
            'id_phong_ban_cha' => $validated['id_phong_ban_cha'] ?? null,
            'id_truong_phong' => $validated['id_truong_phong'] ?? null,
            'tinh_trang' => $validated['tinh_trang'] ?? null,
        ], function ($v) {
            return !is_null($v);
        }));

        return response()->json([
            'status' => true,
            'message' => 'Đổi trạng thái thành công',
            'data' => $phongBan
        ]);
    }

    public function deletePhongBan(Request $request)
    {
        $request->validate(['id' => 'required|exists:phong_bans,id']);
        $phongBan = PhongBan::findOrFail($request->id);
        $phongBan->delete();
        return response()->json([
            'status' => true,
            'message' => 'Xóa thành công',
            'data' => $phongBan
        ]);
    }

    // Lấy tên nhân viên từ id_truong_phong
    public function getTenNhanVienTheoIdTruongPhong(Request $request)
    {
        $validated = $request->validate([
            'id_truong_phong' => 'required|exists:nhan_viens,id',
        ]);

        $nhanVien = NhanVien::findOrFail($validated['id_truong_phong']);

        return response()->json(['ho_va_ten' => $nhanVien->ho_va_ten]);
    }

    public function exportExcel(Request $request)
    {
        $query = PhongBan::with(['phongBanCha', 'truongPhong']);

        if ($request->filled('search')) {
            $query->where('ten_phong_ban', 'like', '%' . $request->search . '%');
        }

        $rows = $query->get();

        $data = $rows->map(function ($item, $idx) {
            return (object) [
                'stt' => $idx + 1,
                'ten_phong_ban' => $item->ten_phong_ban,
                'id_phong_ban_cha' => optional($item->phongBanCha)->ten_phong_ban ?? $item->id_phong_ban_cha,
                'id_truong_phong' => optional($item->truongPhong)->ho_va_ten ?? $item->id_truong_phong,
            ];
        });

        return Excel::download(new ExcelPhongBanExport($data), 'phong_ban_' . date('Ymd_His') . '.xlsx');
    }

    // Wrapper for existing route name
    public function xuatExcelPhongBan(Request $request)
    {
        return $this->exportExcel($request);
    }
}
