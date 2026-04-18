<?php

namespace App\Http\Controllers;

use App\Models\NhanVien;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExcelNhanVienExport;

class NhanVienController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $nhanVien = \App\Models\NhanVien::with(['chucVu'])->where('email', $request->email)->first();

        if (!$nhanVien || !Hash::check($request->password, $nhanVien->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Sai email hoặc mật khẩu'
            ], 401);
        }
        $nhanVien->tokens()->delete();

        $token = $nhanVien->createToken('nhan_vien_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'token' => $token,
            'user' => $nhanVien,
            'role' => $nhanVien->role, // Trả về role để frontend xác định layout
        ]);
    }

    public function exportExcel(Request $request)
    {
        $query = NhanVien::with(['phongBan', 'chucVu']);

        if ($request->filled('phong_ban')) {
            $query->where('id_phong_ban', $request->phong_ban);
        }
        if ($request->filled('id_chuc_vu')) {
            $query->where('id_chuc_vu', $request->id_chuc_vu);
        }
        if ($request->filled('search')) {
            $query->where('ho_va_ten', 'like', '%' . $request->search . '%');
        }

        $rows = $query->get();

        $data = $rows->map(function ($item, $idx) {
            return (object) [
                'stt' => $idx + 1,
                'ho_va_ten' => $item->ho_va_ten,
                'email' => $item->email,
                'ngay_sinh' => $item->ngay_sinh,
                'so_dien_thoai' => $item->so_dien_thoai,
                'dia_chi' => $item->dia_chi,
                'luong_co_ban' => $item->luong_co_ban,
                'ten_chuc_vu' => optional($item->chucVu)->ten_chuc_vu,
                'ten_phong_ban' => optional($item->phongBan)->ten_phong_ban,
            ];
        });

        return Excel::download(new ExcelNhanVienExport($data), 'nhan_vien_' . date('Ymd_His') . '.xlsx');
    }

    // Wrapper for existing route name
    public function xuatExcelNhanVien(Request $request)
    {
        return $this->exportExcel($request);
    }

    public function index(Request $request)
    {
        $query = NhanVien::with(['phongBan', 'chucVu']);

        if ($request->filled('phong_ban')) {
            $query->where('id_phong_ban', $request->phong_ban);
        }
        if ($request->filled('search')) {
            $query->where('ho_va_ten', 'like', '%' . $request->search . '%');
        }

        return response()->json($query->paginate(15));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_phong_ban'  => 'nullable|exists:phong_bans,id',
            'id_chuc_vu'    => 'nullable|exists:chuc_vus,id',
            'ho_va_ten'     => 'required|string|max:100',
            'email'         => 'required|email|unique:nhan_viens',
            'password'      => 'required|string|min:6',
            'ngay_sinh'     => 'nullable|date',
            'dia_chi'       => 'nullable|string',
            'so_dien_thoai' => 'nullable|string|max:15',
            'luong_co_ban'  => 'numeric|min:0',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        return response()->json(NhanVien::create($validated), 201);
    }


    public function update(Request $request)
    {
        $nhanVien = NhanVien::find($request->id);

        if (!$nhanVien) {
            return response()->json([
                'status'  => false,
                'message' => 'Không tìm thấy nhân viên'
            ]);
        }

        $nhanVien->update($request->all());

        return response()->json([
            'status'  => true,
            'message' => 'Cập nhật nhân viên thành công'
        ]);
    }

    public function destroy(Request $request)
    {
        $nhanVien = NhanVien::find($request->id);

        if (!$nhanVien) {
            return response()->json(['status' => false, 'message' => 'Không tìm thấy nhân viên']);
        }

        $nhanVien->delete();
        return response()->json(['status' => true, 'message' => 'Đã xóa nhân viên']);
    }

    public function getData(Request $request)
    {
        $query = NhanVien::with(['phongBan', 'chucVu']);

        if ($request->filled('phong_ban')) {
            $query->where('id_phong_ban', $request->phong_ban);
        }
        if ($request->filled('id_chuc_vu')) {
            $query->where('id_chuc_vu', $request->id_chuc_vu);
        }
        if ($request->filled('search')) {
            $query->where('ho_va_ten', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('per_page')) {
            return response()->json($query->paginate((int) $request->per_page));
        }

        return response()->json($query->select('id', 'ho_va_ten', 'ngay_sinh', 'dia_chi', 'so_dien_thoai', 'luong_co_ban', 'email', 'id_phong_ban', 'id_chuc_vu')->get());
    }

    public function checkLogin()
    {
        $user_login   = Auth::guard('sanctum')->user();
        if ($user_login && $user_login instanceof \App\Models\NhanVien) {
            return response()->json([
                'status'    =>  true,
                'user'      =>  $user_login,
                'role'      =>  $user_login->role
            ]);
        } else {
            return response()->json([
                'status'    =>  false,
                'message'   =>  'Bạn cần đăng nhập vào hệ thống trước!'
            ]);
        }
    }

    public function changeStatus(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:phong_bans,id',
            'tinh_trang' => 'required|in:0,1',
        ]);

        $phongBan = NhanVien::findOrFail($validated['id']);
        $phongBan->update(['tinh_trang' => $validated['tinh_trang']]);
        return response()->json($phongBan);
    }

    public function logout(Request $request)
    {
        $user = $request->user('sanctum');

        if ($user) {
            // Delete current token
            $token = $user->currentAccessToken();
            if ($token) {
                $token->delete();
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Đã đăng xuất'
        ]);
    }
}
