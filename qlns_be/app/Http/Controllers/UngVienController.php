<?php

namespace App\Http\Controllers;

use App\Models\UngVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UngVienController extends Controller
{
    public function index(Request $request)
    {
        $query = UngVien::query();
        if ($request->filled('search')) {
            $query->where('ho_ten', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
        }
        return response()->json($query->paginate(15));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ho_ten'        => 'required|string|max:50',
            'email'         => 'required|email',
            'so_dien_thoai' => 'nullable|string|max:20',
            'file_cv'       => 'nullable|string',
            'ghi_chu'       => 'nullable|string',
        ]);
        return response()->json(UngVien::create($validated), 201);
    }

    public function dangKy(Request $request)
    {
        $validated = $request->validate([
            'ho_ten'   => 'required|string|max:50',
            'email'    => 'required|email|unique:ung_viens',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $ungVien = UngVien::create($validated);

        return response()->json([
            'status'  => true,
            'message' => 'Đăng ký thành công',
            'token'   => $ungVien->createToken('token_ung_vien')->plainTextToken,
            'user'    => $ungVien,
        ], 201);
    }

    public function dangnhap(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $ungVien = UngVien::where('email', $request->email)->first();

        if (!$ungVien || !Hash::check($request->password, $ungVien->password)) {
            return response()->json([
                'status'  => false,
                'message' => 'Email hoặc mật khẩu không đúng'
            ], 401);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Đăng nhập thành công',
            'token'   => $ungVien->createToken('token_ung_vien')->plainTextToken,
            'user'    => $ungVien,
        ]);
    }

    public function checkLogin()
    {
        $user = Auth::user();
        if ($user && $user instanceof \App\Models\UngVien) {
            return response()->json(['status' => true]);
        }
        return response()->json(['status' => false, 'message' => 'Bạn cần đăng nhập vào hệ thống trước!']);
    }

    public function show(UngVien $ungVien)
    {
        return response()->json($ungVien->load('hoSoUngTuyens'));
    }

    public function update(Request $request, UngVien $ungVien)
    {
        $ungVien->update($request->validate([
            'ho_ten'        => 'string|max:50',
            'so_dien_thoai' => 'nullable|string|max:20',
            'file_cv'       => 'nullable|string',
            'ghi_chu'       => 'nullable|string',
            'tinh_trang'    => 'integer|in:0,1',
        ]));
        return response()->json($ungVien);
    }

    public function destroy(UngVien $ungVien)
    {
        $ungVien->delete();
        return response()->json(['message' => 'Đã xóa ứng viên']);
    }
}
