<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DonNghiPhepController;
use App\Http\Controllers\ChucVuController;

use App\Http\Controllers\NhanVienController;
use App\Http\Controllers\PhanQuyenController;
use App\Http\Controllers\PhongBanController;

use App\Http\Controllers\ViTriTuyenDungController;
use App\Http\Controllers\UngVienController;
use App\Http\Controllers\ChamCongAdminController;
use App\Http\Controllers\ChamCongNhanVienController;


//đăng nhập nội bộ
Route::get('/admin/dang-xuat', [NhanVienController::class, 'dangXuat'])->middleware("NhanVienMiddle");
Route::get('/admin/dang-xuat-all', [NhanVienController::class, 'dangXuatAll'])->middleware("NhanVienMiddle");
Route::post('/admin/dang-nhap', [NhanVienController::class, 'login']);
Route::post('/admin/dang-ky', [NhanVienController::class, 'SignIn']);
Route::get('/admin/nhan-vien/data', [NhanVienController::class, 'getData'])->middleware("NhanVienMiddle");
Route::get('/admin/check-login', [NhanVienController::class, 'checkLogin']);
Route::get('/admin/logout', [NhanVienController::class, 'logout'])->middleware("NhanVienMiddle");

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

//chấm công nhân viên
Route::get('/nhan-vien/cham-cong/hom-nay',   [ChamCongNhanVienController::class, 'homNay'])->middleware("auth:sanctum");
Route::get('/nhan-vien/cham-cong/lich-su',   [ChamCongNhanVienController::class, 'lichSu'])->middleware("auth:sanctum");
Route::post('/nhan-vien/cham-cong/check-in', [ChamCongNhanVienController::class, 'checkIn'])->middleware("auth:sanctum");
Route::post('/nhan-vien/cham-cong/check-out', [ChamCongNhanVienController::class, 'checkOut'])->middleware("auth:sanctum");

//chấm công admin
Route::get('admin/cham-cong/data',                [ChamCongAdminController::class, 'getData'])->middleware("NhanVienMiddle");
Route::get('admin/cham-cong/xem-anh/{id}',        [ChamCongAdminController::class, 'xemAnh'])->middleware("NhanVienMiddle");
Route::post('admin/cham-cong/xac-nhan',           [ChamCongAdminController::class, 'xacNhan'])->middleware("NhanVienMiddle");
Route::post('admin/cham-cong/nghi-ngo',           [ChamCongAdminController::class, 'danhDauNghiNgo'])->middleware("NhanVienMiddle");
Route::post('admin/cham-cong/xac-nhan-hang-loat', [ChamCongAdminController::class, 'xacNhanHangLoat'])->middleware("NhanVienMiddle");
Route::get('admin/cham-cong/thong-ke',            [ChamCongAdminController::class, 'thongKe'])->middleware("NhanVienMiddle");

//nghỉ phép nhân viên
Route::get('/nhan-vien/nghi-phep/danh-sach',   [DonNghiPhepController::class, 'danhSachNhanVien'])->middleware("auth:sanctum");;
Route::get('/nhan-vien/nghi-phep/thong-ke',    [DonNghiPhepController::class, 'thongKeNhanVien'])->middleware("auth:sanctum");
Route::post('/nhan-vien/nghi-phep/nop-don',    [DonNghiPhepController::class, 'nopDon'])->middleware("auth:sanctum");
Route::post('/nhan-vien/nghi-phep/huy-don',    [DonNghiPhepController::class, 'huyDon'])->middleware("auth:sanctum");

//nghỉ phép admin
Route::get('/admin/don-nghi-phep/data',        [DonNghiPhepController::class, 'getData'])->middleware("NhanVienMiddle");
Route::post('/admin/don-nghi-phep/duyet',      [DonNghiPhepController::class, 'duyet'])->middleware("NhanVienMiddle");
Route::post('/admin/don-nghi-phep/tu-choi',    [DonNghiPhepController::class, 'tuChoi'])->middleware("NhanVienMiddle");
Route::post('/admin/don-nghi-phep/delete',     [DonNghiPhepController::class, 'delete'])->middleware("NhanVienMiddle");
Route::get('/admin/don-nghi-phep/xuat-excel',  [DonNghiPhepController::class, 'xuatExcel'])->middleware("NhanVienMiddle");
