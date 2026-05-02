<?php

namespace App\Http\Controllers;

use App\Models\NhanVien;
use App\Models\PhanQuyen;
use App\Support\Rbac;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PhanQuyenController extends Controller
{
    public function getListChucNang(Request $request)
    {
        $validated = $request->validate([
            'id' => ['required', 'exists:nhan_viens,id'],
        ]);

        $nhanVien = NhanVien::with('phanQuyens')->findOrFail($validated['id']);
        $assigned = $nhanVien->phanQuyens
            ->pluck('ten_chuc_nang')
            ->map(fn ($permission) => Rbac::normalizePermission($permission))
            ->all();

        if (Rbac::isRootAdmin($nhanVien)) {
            $assigned[] = Rbac::ADMIN_PERMISSION;
        }

        $data = collect(Rbac::assignablePermissions())->map(function ($label, $key) use ($assigned) {
            return [
                'id' => $key,
                'ten_chuc_nang' => $label,
                'is_phan_quyen' => in_array($key, $assigned, true) ? 1 : 0,
            ];
        })->values();

        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }

    public function setQuyen(Request $request)
    {
        $validated = $request->validate([
            'id_nhan_vien' => ['required', 'exists:nhan_viens,id'],
            'id_chuc_nang' => ['required', Rule::in(array_keys(Rbac::assignablePermissions()))],
        ]);

        $nhanVien = NhanVien::findOrFail($validated['id_nhan_vien']);
        if (Rbac::isRootAdmin($nhanVien)) {
            return response()->json([
                'status' => false,
                'message' => 'Không thể thay đổi quyền của admin gốc',
            ], 422);
        }

        PhanQuyen::where('id_nhanvien', $validated['id_nhan_vien'])
            ->whereIn('ten_chuc_nang', array_keys(Rbac::assignablePermissions()))
            ->delete();

        PhanQuyen::firstOrCreate([
            'id_nhanvien' => $validated['id_nhan_vien'],
            'ten_chuc_nang' => $validated['id_chuc_nang'],
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Cấp quyền thành công',
        ]);
    }

    public function delQuyen(Request $request)
    {
        $validated = $request->validate([
            'id_nhan_vien' => ['required', 'exists:nhan_viens,id'],
            'id_chuc_nang' => ['required', Rule::in(array_keys(Rbac::assignablePermissions()))],
        ]);

        $nhanVien = NhanVien::findOrFail($validated['id_nhan_vien']);
        if (Rbac::isRootAdmin($nhanVien)) {
            return response()->json([
                'status' => false,
                'message' => 'Không thể thu hồi quyền của admin gốc',
            ], 422);
        }

        PhanQuyen::where('id_nhanvien', $validated['id_nhan_vien'])
            ->where('ten_chuc_nang', $validated['id_chuc_nang'])
            ->delete();

        return response()->json([
            'status' => true,
            'message' => 'Thu hồi quyền thành công',
        ]);
    }
}
