<?php

namespace App\Http\Controllers;

use App\Models\NhanVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AUTHController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'ho_va_ten'     => 'required|string|max:100',
                'email'         => 'required|email|unique:nhan_viens,email',
                'password'      => 'required|string|min:6',
                'so_dien_thoai' => 'nullable|string|max:15',
            ]);

            $validated['password'] = Hash::make($request->password);

            NhanVien::create($validated);

            return response()->json([
                'status'  => true,
                'message' => 'Đăng ký tài khoản nhân viên thành công!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Lỗi server: ' . $e->getMessage()
            ], 500);
        }
    }

    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email'     => 'required|email',
    //         'password'  => 'required|string',
    //     ]);

    //     $check = Auth::guard('nhanvien')->attempt([
    //         'email'     => $request->email,
    //         'password'  => $request->password
    //     ]);

    //     if ($check) {
    //         $nhanVien = Auth::guard('nhanvien')->user();

    //         return response()->json([
    //             'status'    => true,
    //             'message'   => "Đã đăng nhập thành công!",
    //             'token'     => $nhanVien->createToken('token_nhan_vien')->plainTextToken,
    //         ]);
    //     }

    //     return response()->json([
    //         'status'    => false,
    //         'message'   => "Tài khoản hoặc mật khẩu không đúng!"
    //     ], 401);
    // }




    // public function logout(Request $request)
    // {
    //     $user = Auth::guard('sanctum')->user();

    //     if ($user) {
    //         $user->currentAccessToken()->delete();
    //     }

    //     return response()->json([
    //         'status'  => true,
    //         'message' => 'Đã đăng xuất thành công!'
    //     ]);
    // }

    public function checkLogin()
    {
        $user_login   = Auth::guard('sanctum')->user();
        if ($user_login && $user_login instanceof \App\Models\NhanVien) {
            return response()->json([
                'status'    =>  true
            ]);
        } else {
            return response()->json([
                'status'    =>  false,
                'message'   =>  'Bạn cần đăng nhập vào hệ thống trước!'
            ]);
        }
    }
}
