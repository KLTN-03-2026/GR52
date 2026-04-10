<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ChamCongController;
use App\Http\Controllers\ChiTietHopDongController;
use App\Http\Controllers\ChucVuController;
use App\Http\Controllers\KpiNhanVienController;
use App\Http\Controllers\LoaiHopDongController;
use App\Http\Controllers\NhanVienController;
use App\Http\Controllers\PhanQuyenController;
use App\Http\Controllers\PhongBanController;
use App\Http\Controllers\QuyDinhChoDiemController;
use App\Http\Controllers\ThongBaoController;
use App\Http\Controllers\ThongKeController;
use App\Http\Controllers\ThuongVaPhatController;
use App\Http\Controllers\TieuChiKPIController;
use App\Http\Controllers\ViTriTuyenDungController;
use App\Http\Controllers\UngVienController;
//đăng nhập nội bộ
Route::get('/admin/dang-xuat', [NhanVienController::class, 'dangXuat'])->middleware("NhanVienMiddle");
Route::get('/admin/dang-xuat-all', [NhanVienController::class, 'dangXuatAll'])->middleware("NhanVienMiddle");
Route::post('/admin/dang-nhap', [NhanVienController::class, 'login']);
Route::post('/admin/dang-ky', [NhanVienController::class, 'SignIn']);
Route::get('/admin/nhan-vien/data', [NhanVienController::class, 'getData'])->middleware("NhanVienMiddle");
Route::get('/admin/check-login', [NhanVienController::class, 'checkLogin']);

//nhân viên
Route::post('/admin/nhan-vien/tim-kiem', [NhanVienController::class, 'timKiemNhanVien'])->middleware("NhanVienMiddle");
Route::post('/admin/nhan-vien/create', [NhanVienController::class, 'store'])->middleware("NhanVienMiddle");
Route::post('/admin/nhan-vien/change-status', [NhanVienController::class, 'changeStatus'])->middleware("NhanVienMiddle");
Route::post('/admin/nhan-vien/update', [NhanVienController::class, 'update'])->middleware("NhanVienMiddle");
Route::post('/admin/nhan-vien/delete', [NhanVienController::class, 'destroy'])->middleware("NhanVienMiddle");
Route::get('/admin/nhan-vien/xuat-excel', [NhanVienController::class, 'xuatExcelNhanVien'])->middleware("NhanVienMiddle");
Route::post('/admin/tinh-luong', [NhanVienController::class, 'tinhLuong'])->middleware("NhanVienMiddle");
Route::post('/admin/luong/xuat-excel', [NhanVienController::class, 'xuatExcelLuong'])->middleware("NhanVienMiddle");

//chức vụ
Route::get('/admin/chuc-vu/data', [ChucVuController::class, 'getData'])->middleware("NhanVienMiddle");
Route::post('/admin/chuc-vu/create', [ChucVuController::class, 'store'])->middleware("NhanVienMiddle");
Route::post('/admin/chuc-vu/change-status', [ChucVuController::class, 'changeStatus'])->middleware("NhanVienMiddle");
Route::post('/admin/chuc-vu/update', [ChucVuController::class, 'updateChucVu'])->middleware("NhanVienMiddle");
Route::post('/admin/chuc-vu/delete', [ChucVuController::class, 'deleteChucVu'])->middleware("NhanVienMiddle");
Route::get('/admin/chuc-vu/xuat-excel', [ChucVuController::class, 'xuatExcelChucVu'])->middleware("NhanVienMiddle");

//phòng ban
Route::get('/admin/phong-ban/data', [PhongBanController::class, 'getData'])->middleware("NhanVienMiddle");
Route::post('/admin/phong-ban/create', [PhongBanController::class, 'store'])->middleware("NhanVienMiddle");
Route::post('/admin/phong-ban/change-status', [PhongBanController::class, 'changeStatus'])->middleware("NhanVienMiddle");
Route::post('/admin/phong-ban/update', [PhongBanController::class, 'updatePhongBan'])->middleware("NhanVienMiddle");
Route::post('/admin/phong-ban/delete', [PhongBanController::class, 'deletePhongBan'])->middleware("NhanVienMiddle");
Route::get('/admin/phong-ban/xuat-excel', [PhongBanController::class, 'xuatExcelPhongBan'])->middleware("NhanVienMiddle");

//chức năng phân quyền
Route::post('/admin/chuc-nang/data', [PhanQuyenController::class, 'getListChucNang'])->middleware("NhanVienMiddle");
Route::post('/admin/phan-quyen/create', [PhanQuyenController::class, 'setQuyen'])->middleware("NhanVienMiddle");
Route::post('/admin/phan-quyen/delete', [PhanQuyenController::class, 'delQuyen'])->middleware("NhanVienMiddle");

// Vị trí tuyển dụng
Route::get('/admin/vi-tri/data', [ViTriTuyenDungController::class, 'getData'])->middleware("NhanVienMiddle");
Route::post('/admin/vi-tri/create', [ViTriTuyenDungController::class, 'store'])->middleware("NhanVienMiddle");
Route::post('/admin/vi-tri/update', [ViTriTuyenDungController::class, 'update'])->middleware("NhanVienMiddle");
Route::post('/admin/vi-tri/delete', [ViTriTuyenDungController::class, 'destroy'])->middleware("NhanVienMiddle");
Route::get('/vi-tri/show/{viTriTuyenDung}', [ViTriTuyenDungController::class, 'show'])->middleware("NhanVienMiddle");
Route::get('/vi-tri/open', [ViTriTuyenDungController::class, 'getDataOpen']);

//đăng nhập ứng viên
Route::post('/ung-vien/dang-nhap', [UngVienController::class, 'dangNhap']);
Route::get('/ung-vien/check-login', [UngVienController::class, 'checkLogin']);
Route::get('/ung-vien/show/{ungVien}', [UngVienController::class, 'show'])->middleware("auth:ung_vien");
Route::post('/ung-vien/dang-xuat', [UngVienController::class, 'dangXuat']);
Route::post('/ung-vien/update', [UngVienController::class, 'update'])->middleware("auth:ung_vien");
Route::post('/ung-vien/dang-ky', [UngVienController::class, 'dangKy']);


