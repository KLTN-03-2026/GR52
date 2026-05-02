<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ThongKeController;
use App\Http\Controllers\DonNghiPhepController;
use App\Http\Controllers\ChucVuController;
use App\Http\Controllers\KpiNhanVienController;
use App\Http\Controllers\NhanVienController;
use App\Http\Controllers\PhanQuyenController;
use App\Http\Controllers\PhongBanController;
use App\Http\Controllers\ThuongVaPhatController;
use App\Http\Controllers\ViTriTuyenDungController;
use App\Http\Controllers\UngVienController;
use App\Http\Controllers\ChamCongAdminController;
use App\Http\Controllers\ChamCongNhanVienController;
use App\Http\Controllers\LuongController;
use App\Http\Controllers\HoSoUngTuyenController;
use App\Http\Controllers\LoaiHopDongController;
use App\Http\Controllers\Api\JobSuggestionController;
use App\Models\KpiNhanVien;

//đăng nhập nội bộ
Route::get('/admin/dang-xuat', [NhanVienController::class, 'dangXuat'])->middleware("NhanVienMiddle");
Route::get('/admin/dang-xuat-all', [NhanVienController::class, 'dangXuatAll'])->middleware("NhanVienMiddle");
Route::post('/admin/dang-nhap', [NhanVienController::class, 'login']);
Route::post('/admin/dang-ky', [NhanVienController::class, 'SignIn']);
Route::get('/admin/nhan-vien/data', [NhanVienController::class, 'getData'])->middleware("NhanVienMiddle");
Route::get('/admin/check-login', [NhanVienController::class, 'checkLogin']);
Route::get('/admin/logout', [NhanVienController::class, 'logout'])->middleware("NhanVienMiddle");
Route::get('/admin/user-info', [NhanVienController::class, 'getUserInfo'])->middleware("auth:sanctum");

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

//phân quyền ADMIN / HR
Route::post('/admin/chuc-nang/data', [PhanQuyenController::class, 'getListChucNang'])->middleware(["NhanVienMiddle", "role:admin"]);
Route::post('/admin/phan-quyen/create', [PhanQuyenController::class, 'setQuyen'])->middleware(["NhanVienMiddle", "role:admin"]);
Route::post('/admin/phan-quyen/delete', [PhanQuyenController::class, 'delQuyen'])->middleware(["NhanVienMiddle", "role:admin"]);

// Vị trí tuyển dụng
Route::get('/admin/vi-tri/data', [ViTriTuyenDungController::class, 'getData'])->middleware("NhanVienMiddle");
Route::post('/admin/vi-tri/create', [ViTriTuyenDungController::class, 'store'])->middleware("NhanVienMiddle");
Route::post('/admin/vi-tri/update', [ViTriTuyenDungController::class, 'update'])->middleware("NhanVienMiddle");
Route::post('/admin/vi-tri/delete', [ViTriTuyenDungController::class, 'destroy'])->middleware("NhanVienMiddle"); // Admin delete job
Route::get('/admin/vi-tri/show/{viTriTuyenDung}', [ViTriTuyenDungController::class, 'show'])->middleware("NhanVienMiddle"); // Admin view job details
Route::get('/vi-tri/open', [ViTriTuyenDungController::class, 'getDataOpen']);
Route::get('/vi-tri/{viTriTuyenDung}', [ViTriTuyenDungController::class, 'showPublic']); // Public job detail page for candidates

// Loại hợp đồng (contracts) - admin
Route::get('/admin/loai-hop-dong/data', [LoaiHopDongController::class, 'index'])->middleware("NhanVienMiddle");

//đăng nhập ứng viên
Route::post('/ung-vien/dang-nhap', [UngVienController::class, 'dangNhap']);
Route::get('/ung-vien/check-login', [UngVienController::class, 'checkLogin'])->middleware('auth:sanctum');

// Candidate application routes
Route::post('/ung-vien/ung-tuyen/{viTriTuyenDung}', [HoSoUngTuyenController::class, 'nopHoSo'])->middleware("auth:ung_vien");
Route::get('/ung-vien/ho-so-ung-tuyen', [HoSoUngTuyenController::class, 'getUngVienHoSoUngTuyen'])->middleware("auth:ung_vien");

// HR/Admin application management routes
Route::get('/admin/vi-tri/{viTriTuyenDung}/ung-tuyen', [HoSoUngTuyenController::class, 'getHoSoUngTuyenByViTri'])->middleware("NhanVienMiddle");
Route::get('/admin/ung-tuyen/all', [HoSoUngTuyenController::class, 'getAllApplications'])->middleware("NhanVienMiddle");
Route::get('/admin/ung-tuyen/{hoSoUngTuyen}/download-cv', [HoSoUngTuyenController::class, 'downloadCv'])->middleware("NhanVienMiddle");
Route::get('/admin/ung-tuyen/{hoSoUngTuyen}/preview-cv', [HoSoUngTuyenController::class, 'previewCv'])->middleware("NhanVienMiddle");
Route::post('/admin/ung-tuyen/{hoSoUngTuyen}/update-status', [HoSoUngTuyenController::class, 'updateTrangThai'])->middleware("NhanVienMiddle");
Route::get('/ung-vien/show/{ungVien}', [UngVienController::class, 'show'])->middleware("auth:ung_vien");
Route::post('/ung-vien/dang-xuat', [UngVienController::class, 'dangXuat']);
Route::post('/ung-vien/update', [UngVienController::class, 'update'])->middleware("auth:ung_vien");
Route::post('/ung-vien/nop-cv', [UngVienController::class, 'nopCv'])->middleware("auth:ung_vien");
Route::post('/ung-vien/dang-ky', [UngVienController::class, 'dangKy']);
Route::get('/ung-vien/goi-y-viec-lam', [JobSuggestionController::class, 'index'])->middleware("auth:ung_vien");
Route::post('/ung-vien/goi-y-viec-lam/refresh', [JobSuggestionController::class, 'refresh'])->middleware("auth:ung_vien");

//chấm công nhân viên
Route::get('/nhan-vien/cham-cong/hom-nay',   [ChamCongNhanVienController::class, 'homNay'])->middleware("auth:sanctum");
Route::get('/nhan-vien/cham-cong/lich-su',   [ChamCongNhanVienController::class, 'lichSu'])->middleware("auth:sanctum");
Route::post('/nhan-vien/cham-cong/check-in', [ChamCongNhanVienController::class, 'checkIn'])->middleware("auth:sanctum");
Route::post('/nhan-vien/cham-cong/check-out', [ChamCongNhanVienController::class, 'checkOut'])->middleware("auth:sanctum");

//chấm công admin
Route::get('admin/cham-cong/data',                [ChamCongAdminController::class, 'getData'])->middleware("NhanVienMiddle");
Route::get('admin/cham-cong/xem-anh/{id}',        [ChamCongAdminController::class, 'xemAnh'])->middleware("NhanVienMiddle");
Route::get('admin/cham-cong/download-anh-checkin/{id}',  [ChamCongAdminController::class, 'downloadAnhCheckin'])->middleware("NhanVienMiddle");
Route::get('admin/cham-cong/download-anh-checkout/{id}', [ChamCongAdminController::class, 'downloadAnhCheckout'])->middleware("NhanVienMiddle");
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

// Thống kê
Route::get('/admin/thong-ke/cham-cong',           [ThongKeController::class, 'chamCong'])->middleware("NhanVienMiddle");
Route::get('/admin/thong-ke/nghi-phep',           [ThongKeController::class, 'nghiPhep'])->middleware("NhanVienMiddle");
Route::get('/admin/thong-ke/tong-quan',           [ThongKeController::class, 'tongQuan'])->middleware("NhanVienMiddle");
Route::get('/admin/thong-ke/phong-ban',           [ThongKeController::class, 'phongBan'])->middleware("NhanVienMiddle");
Route::get('/admin/thong-ke/chuc-vu',             [ThongKeController::class, 'chucVu'])->middleware("NhanVienMiddle");
Route::get('/admin/thong-ke/canh-bao',            [ThongKeController::class, 'canhBao'])->middleware("NhanVienMiddle");

//KPI Nhân Viên
Route::get('/admin/kpi/tieu-chi',         [KpiNhanVienController::class, 'danhSachTieuChi'])->middleware("NhanVienMiddle");
Route::post('/admin/kpi/tieu-chi/create', [KpiNhanVienController::class, 'taotTieuChi'])->middleware("NhanVienMiddle");
Route::post('/admin/kpi/tieu-chi/update', [KpiNhanVienController::class, 'suaTieuChi'])->middleware("NhanVienMiddle");
Route::post('/admin/kpi/tieu-chi/delete', [KpiNhanVienController::class, 'xoaTieuChi'])->middleware("NhanVienMiddle");
Route::get('/admin/kpi/nhan-vien',           [KpiNhanVienController::class, 'danhSachKpiNV'])->middleware("NhanVienMiddle");
Route::post('/admin/kpi/nhan-vien/gan',      [KpiNhanVienController::class, 'ganKpiNV'])->middleware("NhanVienMiddle");
Route::post('/admin/kpi/nhan-vien/ket-qua',  [KpiNhanVienController::class, 'nhapKetQua'])->middleware("NhanVienMiddle");
Route::post('/admin/kpi/nhan-vien/delete',   [KpiNhanVienController::class, 'xoaKpiNV'])->middleware("NhanVienMiddle");

//thưởng phạt
Route::get('/admin/thuong-phat/data',     [ThuongVaPhatController::class, 'getData'])->middleware("NhanVienMiddle");
Route::post('/admin/thuong-phat/create',  [ThuongVaPhatController::class, 'create'])->middleware("NhanVienMiddle");
Route::post('/admin/thuong-phat/update',  [ThuongVaPhatController::class, 'update'])->middleware("NhanVienMiddle");
Route::post('/admin/thuong-phat/delete',  [ThuongVaPhatController::class, 'delete'])->middleware("NhanVienMiddle");

//lương thưởng
Route::post('/admin/luong/tinh-luong',      [LuongController::class, 'tinhLuong'])->middleware("NhanVienMiddle");
Route::get('/admin/luong/bang-luong',       [LuongController::class, 'getBangLuong'])->middleware("NhanVienMiddle");
Route::post('/admin/luong/doi-trang-thai',  [LuongController::class, 'doiTrangThai'])->middleware("NhanVienMiddle");
Route::get('/admin/luong/xuat-excel',       [LuongController::class, 'xuatExcel'])->middleware("NhanVienMiddle");

// ── NHÂN VIÊN XEM THÔNG TIN CÁ NHÂN ────────────────────────────────────
// Bảng lương
Route::get('/nhan-vien/luong/xem',           [LuongController::class, 'viewMyLuong'])->middleware("auth:sanctum");

// KPI
Route::get('/nhan-vien/kpi/xem',             [KpiNhanVienController::class, 'viewMyKpi'])->middleware("auth:sanctum");

// Thưởng và Phạt
Route::get('/nhan-vien/thuong-va-phat/xem',  [ThuongVaPhatController::class, 'viewMyThuongVaPhat'])->middleware("auth:sanctum");

// ── CV ANALYSIS (AI-Powered Recruitment Scoring) ────────────────────────
Route::prefix('/admin/cv-analysis')->middleware(['NhanVienMiddle'])->group(function () {
    Route::post('/analyze-single', [App\Http\Controllers\Api\CVAnalysisController::class, 'analyzeSingle']);
    Route::post('/analyze-batch', [App\Http\Controllers\Api\CVAnalysisController::class, 'analyzeBatch']);
    Route::get('/{hoSoUngTuyenId}', [App\Http\Controllers\Api\CVAnalysisController::class, 'getAnalysis']);
    Route::get('/job/{viTriId}', [App\Http\Controllers\Api\CVAnalysisController::class, 'getByPosition']);
});
