<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KpiNhanVienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // Nhân viên 3 - Lê Hoàng Cường (3 tháng)
            ['id_nhan_vien' => 3, 'id_tieu_chi' => 1, 'thang' => 4, 'nam' => 2026, 'muc_tieu' => 100.0, 'ket_qua_thuc_te' => 95.0, 'phan_tram_hoan_thanh' => 95.0, 'diem_kpi' => 28.50, 'xep_loai' => 'B', 'ghi_chu' => 'Hoàn thành 95% công việc đúng hạn'],
            ['id_nhan_vien' => 3, 'id_tieu_chi' => 2, 'thang' => 4, 'nam' => 2026, 'muc_tieu' => 100.0, 'ket_qua_thuc_te' => 88.0, 'phan_tram_hoan_thanh' => 88.0, 'diem_kpi' => 22.00, 'xep_loai' => 'B', 'ghi_chu' => 'Chất lượng tốt'],
            ['id_nhan_vien' => 3, 'id_tieu_chi' => 3, 'thang' => 4, 'nam' => 2026, 'muc_tieu' => 100.0, 'ket_qua_thuc_te' => 95.0, 'phan_tram_hoan_thanh' => 95.0, 'diem_kpi' => 19.00, 'xep_loai' => 'B', 'ghi_chu' => 'Chuyên cần'],

            // Nhân viên 5 - Võ Minh Đức
            ['id_nhan_vien' => 5, 'id_tieu_chi' => 1, 'thang' => 4, 'nam' => 2026, 'muc_tieu' => 100.0, 'ket_qua_thuc_te' => 100.0, 'phan_tram_hoan_thanh' => 100.0, 'diem_kpi' => 30.00, 'xep_loai' => 'A', 'ghi_chu' => 'Hoàn thành 100% đúng hạn'],
            ['id_nhan_vien' => 5, 'id_tieu_chi' => 2, 'thang' => 4, 'nam' => 2026, 'muc_tieu' => 100.0, 'ket_qua_thuc_te' => 92.0, 'phan_tram_hoan_thanh' => 92.0, 'diem_kpi' => 23.00, 'xep_loai' => 'B', 'ghi_chu' => 'Chất lượng tốt'],
            ['id_nhan_vien' => 5, 'id_tieu_chi' => 3, 'thang' => 4, 'nam' => 2026, 'muc_tieu' => 100.0, 'ket_qua_thuc_te' => 85.0, 'phan_tram_hoan_thanh' => 85.0, 'diem_kpi' => 17.00, 'xep_loai' => 'B', 'ghi_chu' => 'Đứng giờ tốt'],

            // Nhân viên 6 - Đặng Thị Hoa
            ['id_nhan_vien' => 6, 'id_tieu_chi' => 1, 'thang' => 4, 'nam' => 2026, 'muc_tieu' => 100.0, 'ket_qua_thuc_te' => 100.0, 'phan_tram_hoan_thanh' => 100.0, 'diem_kpi' => 30.00, 'xep_loai' => 'A', 'ghi_chu' => 'Xuất sắc'],
            ['id_nhan_vien' => 6, 'id_tieu_chi' => 4, 'thang' => 4, 'nam' => 2026, 'muc_tieu' => 100.0, 'ket_qua_thuc_te' => 95.0, 'phan_tram_hoan_thanh' => 95.0, 'diem_kpi' => 9.50, 'xep_loai' => 'B', 'ghi_chu' => 'Tham gia khóa đào tạo'],
            ['id_nhan_vien' => 6, 'id_tieu_chi' => 5, 'thang' => 4, 'nam' => 2026, 'muc_tieu' => 100.0, 'ket_qua_thuc_te' => 90.0, 'phan_tram_hoan_thanh' => 90.0, 'diem_kpi' => 9.00, 'xep_loai' => 'B', 'ghi_chu' => 'Làm việc nhóm tốt'],

            // Nhân viên 7 - Bùi Quốc Khánh (Kinh doanh)
            ['id_nhan_vien' => 7, 'id_tieu_chi' => 8, 'thang' => 4, 'nam' => 2026, 'muc_tieu' => 100.0, 'ket_qua_thuc_te' => 110.0, 'phan_tram_hoan_thanh' => 110.0, 'diem_kpi' => 55.00, 'xep_loai' => 'A', 'ghi_chu' => 'Vượt 10% doanh số'],

            // Nhân viên 8 - Nguyễn Ngọc Lam
            ['id_nhan_vien' => 8, 'id_tieu_chi' => 1, 'thang' => 4, 'nam' => 2026, 'muc_tieu' => 100.0, 'ket_qua_thuc_te' => 98.0, 'phan_tram_hoan_thanh' => 98.0, 'diem_kpi' => 29.40, 'xep_loai' => 'A', 'ghi_chu' => 'Rất tốt'],
            ['id_nhan_vien' => 8, 'id_tieu_chi' => 2, 'thang' => 4, 'nam' => 2026, 'muc_tieu' => 100.0, 'ket_qua_thuc_te' => 95.0, 'phan_tram_hoan_thanh' => 95.0, 'diem_kpi' => 23.75, 'xep_loai' => 'B', 'ghi_chu' => 'Tốt'],
        ];

        foreach ($data as $item) {
            DB::table('kpi_nhan_viens')->insertOrIgnore(array_merge($item, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
