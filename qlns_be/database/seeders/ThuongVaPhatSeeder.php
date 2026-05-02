<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ThuongVaPhatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // Thưởng
            ['id_nhan_vien' => 3, 'thang' => 4, 'nam' => 2026, 'loai' => 'thuong', 'so_tien' => 500000, 'ly_do' => 'Hoàn thành dự án web tuyệt vời', 'id_nguoi_tao' => 2],
            ['id_nhan_vien' => 5, 'thang' => 4, 'nam' => 2026, 'loai' => 'thuong', 'so_tien' => 1000000, 'ly_do' => 'Đạt 100% KPI tháng 4', 'id_nguoi_tao' => 2],
            ['id_nhan_vien' => 6, 'thang' => 4, 'nam' => 2026, 'loai' => 'thuong', 'so_tien' => 300000, 'ly_do' => 'Giải pháp đổi mới trong quy trình', 'id_nguoi_tao' => 3],
            ['id_nhan_vien' => 7, 'thang' => 4, 'nam' => 2026, 'loai' => 'thuong', 'so_tien' => 2000000, 'ly_do' => 'Đạt 110% doanh số tháng 4', 'id_nguoi_tao' => 2],
            ['id_nhan_vien' => 8, 'thang' => 4, 'nam' => 2026, 'loai' => 'thuong', 'so_tien' => 200000, 'ly_do' => 'Hỗ trợ ngoài giờ hoàn thành release', 'id_nguoi_tao' => 3],

            // Phạt
            ['id_nhan_vien' => 4, 'thang' => 4, 'nam' => 2026, 'loai' => 'phat', 'so_tien' => 100000, 'ly_do' => 'Đến muộn 3 lần trong tháng 4', 'id_nguoi_tao' => 2],
            ['id_nhan_vien' => 5, 'thang' => 3, 'nam' => 2026, 'loai' => 'phat', 'so_tien' => 200000, 'ly_do' => 'Nộp báo cáo trễ 2 ngày', 'id_nguoi_tao' => 2],
            ['id_nhan_vien' => 8, 'thang' => 3, 'nam' => 2026, 'loai' => 'phat', 'so_tien' => 50000, 'ly_do' => null, 'id_nguoi_tao' => 3],
        ];

        foreach ($data as $item) {
            DB::table('thuong_va_phats')->insertOrIgnore(array_merge($item, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
