<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ViTriTuyenDungSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['id_phong_ban' => 3, 'id_chuc_vu' => 5, 'tieu_de' => 'Lập trình viên Backend PHP',    'so_luong' => 2, 'ngay_bat_dau' => '2024-10-01', 'ngay_ket_thuc' => '2024-11-30', 'tinh_trang' => 0],
            ['id_phong_ban' => 3, 'id_chuc_vu' => 5, 'tieu_de' => 'Lập trình viên Frontend React', 'so_luong' => 1, 'ngay_bat_dau' => '2024-10-01', 'ngay_ket_thuc' => '2024-11-30', 'tinh_trang' => 0],
            ['id_phong_ban' => 5, 'id_chuc_vu' => 5, 'tieu_de' => 'Chuyên viên Marketing Digital', 'so_luong' => 1, 'ngay_bat_dau' => '2024-11-01', 'ngay_ket_thuc' => '2024-12-31', 'tinh_trang' => 1],
            ['id_phong_ban' => 2, 'id_chuc_vu' => 5, 'tieu_de' => 'Chuyên viên tuyển dụng',        'so_luong' => 1, 'ngay_bat_dau' => '2024-11-15', 'ngay_ket_thuc' => '2025-01-15', 'tinh_trang' => 1],
            ['id_phong_ban' => 4, 'id_chuc_vu' => 5, 'tieu_de' => 'Kế toán tổng hợp',             'so_luong' => 1, 'ngay_bat_dau' => '2024-12-01', 'ngay_ket_thuc' => '2025-01-31', 'tinh_trang' => 1],
            ['id_phong_ban' => 6, 'id_chuc_vu' => 5, 'tieu_de' => 'Nhân viên kinh doanh B2B',      'so_luong' => 3, 'ngay_bat_dau' => '2024-12-01', 'ngay_ket_thuc' => '2025-02-28', 'tinh_trang' => 1],
            ['id_phong_ban' => 3, 'id_chuc_vu' => 7, 'tieu_de' => 'Thực tập sinh Công nghệ thông tin', 'so_luong' => 5, 'ngay_bat_dau' => '2025-01-06', 'ngay_ket_thuc' => '2025-06-30', 'tinh_trang' => 1],
            ['id_phong_ban' => 3, 'id_chuc_vu' => 4, 'tieu_de' => 'Phó phòng Công nghệ thông tin', 'so_luong' => 1, 'ngay_bat_dau' => '2025-01-01', 'ngay_ket_thuc' => '2025-03-31', 'tinh_trang' => 1],
        ];

        foreach ($data as $item) {
            DB::table('vi_tri_tuyen_dungs')->insertOrIgnore(array_merge($item, [
                'mo_ta'      => 'Mô tả chi tiết công việc vị trí: ' . $item['tieu_de'],
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
