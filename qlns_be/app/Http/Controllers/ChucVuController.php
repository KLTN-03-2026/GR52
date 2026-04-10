<?php

namespace App\Http\Controllers;

use App\Models\ChucVu;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExcelChucVuExport;

class ChucVuController extends Controller
{
    public function getData(Request $request)
    {
        $query = ChucVu::query();

        if ($request->filled('search')) {
            $query->where('ten_chuc_vu', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('per_page')) {
            return response()->json($query->paginate((int) $request->per_page));
        }

        return response()->json($query->select('id', 'ten_chuc_vu', 'tinh_trang')->get());
    }

    public function getDataOpen(Request $request)
    {
        $query = ChucVu::query();
        if ($request->filled('search')) {
            $query->where('ten_chuc_vu', 'like', '%' . $request->search . '%');
        }
        return response()->json($query->select('id', 'ten_chuc_vu')->get());
    }

    public function index()
    {
        return response()->json(ChucVu::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ten_chuc_vu' => 'required|string|max:100|unique:chuc_vus',
            'tinh_trang'  => 'integer|in:0,1',
        ]);
        $chucVu = ChucVu::create($validated);
        return response()->json([
            'status' => true,
            'message' => 'Them mới thành công',
            'data' => $chucVu
        ]);
    }


    // Route wrappers for POST endpoints
    public function changeStatus(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:phong_bans,id',
            'tinh_trang' => 'required|in:0,1',
        ]);

        $phongBan = ChucVu::findOrFail($validated['id']);
        $phongBan->update(['tinh_trang' => $validated['tinh_trang']]);
        return response()->json([
            'status' => true,
            'message' => 'Đổi trạng thái thành công',
            'data' => $phongBan
        ]);
    }

    public function updateChucVu(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:chuc_vus,id',
            'ten_chuc_vu' => 'string|max:100',
            'tinh_trang'  => 'integer|in:0,1',
        ]);

        $chucVu = ChucVu::findOrFail($validated['id']);
        $chucVu->update(array_filter([
            'ten_chuc_vu' => $validated['ten_chuc_vu'] ?? null,
            'tinh_trang' => $validated['tinh_trang'] ?? null,
        ], function ($v) {
            return !is_null($v);
        }));

        return response()->json([
            'status' => true,
            'message' => 'Đổi trạng thái thành công',
            'data' => $chucVu
        ]);
    }

    public function deleteChucVu(Request $request)
    {
        $request->validate(['id' => 'required|exists:chuc_vus,id']);
        $chucVu = ChucVu::findOrFail($request->id);
        $chucVu->delete();
        return response()->json([
            'status' => true,
            'message' => 'xoa thành công',
            'data' => $chucVu
        ]);
    }

    public function exportExcel(Request $request)
    {
        $query = ChucVu::query();

        if ($request->filled('search')) {
            $query->where('ten_chuc_vu', 'like', '%' . $request->search . '%');
        }

        $rows = $query->get();

        $data = $rows->map(function ($item, $idx) {
            return (object) [
                'stt' => $idx + 1,
                'ten_chuc_vu' => $item->ten_chuc_vu,
            ];
        });

        return Excel::download(new ExcelChucVuExport($data), 'chuc_vu_' . date('Ymd_His') . '.xlsx');
    }

    // Wrapper for existing route name
    public function xuatExcelChucVu(Request $request)
    {
        return $this->exportExcel($request);
    }
}
