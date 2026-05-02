<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LuongSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // Nhân viên 3 - Lê Hoàng Cường - Lương cơ bản 28.000.000
            [
                'id_nhan_vien' => 3,
                'thang' => 4,
                'nam' => 2026,
                'luong_co_ban' => 28000000,
                'so_ngay_lam_viec_chuan' => 26,
                'so_gio_lam_thuc_te' => 208.00,
                'gio_chuan_ngay' => 8.0,
                'so_ngay_nghi_co_phep' => 1,
                'so_ngay_nghi_khong_luong' => 0,
                'tong_diem_kpi' => 69.50,
                'xep_loai_kpi' => 'B',
                'he_so_kpi' => 1.0,
                'tong_thuong' => 500000,
                'tong_phat' => 0,
                'luong_ngay' => 1076923.08,
                'luong_thuc_te' => 28000000,
                'thuong_kpi' => 0,
                'khau_tru_nghi' => 0,
                'luong_truoc_thue' => 28500000,
                'thue_tncn' => 0,
                'luong_thuc_nhan' => 28500000,
                'trang_thai' => 'da_duyet',
                'ghi_chu' => 'Lương tháng 4/2026'
            ],
            // Nhân viên 5 - Võ Minh Đức - Lương cơ bản 15.000.000
            [
                'id_nhan_vien' => 5,
                'thang' => 4,
                'nam' => 2026,
                'luong_co_ban' => 15000000,
                'so_ngay_lam_viec_chuan' => 26,
                'so_gio_lam_thuc_te' => 208.00,
                'gio_chuan_ngay' => 8.0,
                'so_ngay_nghi_co_phep' => 0,
                'so_ngay_nghi_khong_luong' => 1,
                'tong_diem_kpi' => 70.00,
                'xep_loai_kpi' => 'A',
                'he_so_kpi' => 1.2,
                'tong_thuong' => 1000000,
                'tong_phat' => 200000,
                'luong_ngay' => 576923.08,
                'luong_thuc_te' => 14423077.00,
                'thuong_kpi' => 3000000,
                'khau_tru_nghi' => 576923,
                'luong_truoc_thue' => 17223077,
                'thue_tncn' => 0,
                'luong_thuc_nhan' => 17223077,
                'trang_thai' => 'da_duyet',
                'ghi_chu' => 'Lương tháng 4/2026 - KPI loại A'
            ],
            // Nhân viên 6 - Đặng Thị Hoa - Lương cơ bản 18.000.000
            [
                'id_nhan_vien' => 6,
                'thang' => 4,
                'nam' => 2026,
                'luong_co_ban' => 18000000,
                'so_ngay_lam_viec_chuan' => 26,
                'so_gio_lam_thuc_te' => 208.00,
                'gio_chuan_ngay' => 8.0,
                'so_ngay_nghi_co_phep' => 0,
                'so_ngay_nghi_khong_luong' => 0,
                'tong_diem_kpi' => 48.50,
                'xep_loai_kpi' => 'B',
                'he_so_kpi' => 1.0,
                'tong_thuong' => 300000,
                'tong_phat' => 0,
                'luong_ngay' => 692307.69,
                'luong_thuc_te' => 18000000,
                'thuong_kpi' => 0,
                'khau_tru_nghi' => 0,
                'luong_truoc_thue' => 18300000,
                'thue_tncn' => 0,
                'luong_thuc_nhan' => 18300000,
                'trang_thai' => 'da_duyet',
                'ghi_chu' => 'Lương tháng 4/2026'
            ],
            // Nhân viên 7 - Bùi Quốc Khánh - Lương cơ bản 17.000.000
            [
                'id_nhan_vien' => 7,
                'thang' => 4,
                'nam' => 2026,
                'luong_co_ban' => 17000000,
                'so_ngay_lam_viec_chuan' => 26,
                'so_gio_lam_thuc_te' => 208.00,
                'gio_chuan_ngay' => 8.0,
                'so_ngay_nghi_co_phep' => 0,
                'so_ngay_nghi_khong_luong' => 0,
                'tong_diem_kpi' => 55.00,
                'xep_loai_kpi' => 'A',
                'he_so_kpi' => 1.2,
                'tong_thuong' => 2000000,
                'tong_phat' => 0,
                'luong_ngay' => 653846.15,
                'luong_thuc_te' => 17000000,
                'thuong_kpi' => 4080000,
                'khau_tru_nghi' => 0,
                'luong_truoc_thue' => 23080000,
                'thue_tncn' => 0,
                'luong_thuc_nhan' => 23080000,
                'trang_thai' => 'da_duyet',
                'ghi_chu' => 'Lương tháng 4/2026 - KPI loại A'
            ],
            // Nhân viên 8 - Nguyễn Ngọc Lam - Lương cơ bản 10.000.000
            [
                'id_nhan_vien' => 8,
                'thang' => 4,
                'nam' => 2026,
                'luong_co_ban' => 10000000,
                'so_ngay_lam_viec_chuan' => 26,
                'so_gio_lam_thuc_te' => 208.00,
                'gio_chuan_ngay' => 8.0,
                'so_ngay_nghi_co_phep' => 0,
                'so_ngay_nghi_khong_luong' => 0,
                'tong_diem_kpi' => 53.15,
                'xep_loai_kpi' => 'A',
                'he_so_kpi' => 1.2,
                'tong_thuong' => 200000,
                'tong_phat' => 50000,
                'luong_ngay' => 384615.38,
                'luong_thuc_te' => 10000000,
                'thuong_kpi' => 2400000,
                'khau_tru_nghi' => 0,
                'luong_truoc_thue' => 12550000,
                'thue_tncn' => 0,
                'luong_thuc_nhan' => 12550000,
                'trang_thai' => 'da_duyet',
                'ghi_chu' => 'Lương tháng 4/2026 - KPI loại A'
            ],
            // Nhân viên 4 - Phạm Thị Dung - Lương cơ bản 22.000.000 (Tháng 3)
            [
                'id_nhan_vien' => 4,
                'thang' => 3,
                'nam' => 2026,
                'luong_co_ban' => 22000000,
                'so_ngay_lam_viec_chuan' => 26,
                'so_gio_lam_thuc_te' => 208.00,
                'gio_chuan_ngay' => 8.0,
                'so_ngay_nghi_co_phep' => 0,
                'so_ngay_nghi_khong_luong' => 0,
                'tong_diem_kpi' => 0,
                'xep_loai_kpi' => null,
                'he_so_kpi' => 1.0,
                'tong_thuong' => 0,
                'tong_phat' => 100000,
                'luong_ngay' => 846153.85,
                'luong_thuc_te' => 22000000,
                'thuong_kpi' => 0,
                'khau_tru_nghi' => 0,
                'luong_truoc_thue' => 21900000,
                'thue_tncn' => 0,
                'luong_thuc_nhan' => 21900000,
                'trang_thai' => 'nhap',
                'ghi_chu' => 'Lương tháng 3/2026'
            ],
        ];

        foreach ($data as $item) {
            DB::table('luongs')->insertOrIgnore(array_merge($item, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
