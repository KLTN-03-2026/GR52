<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class HoSoUngTuyenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['id_ung_vien' => 1, 'id_chuc_vu' => 5, 'ngay_ung_tuyen' => '2024-10-05', 'trang_thai' => 3, 'trang_thai_cu' => '2', 'ghi_chu' => 'Đã phỏng vấn vòng 2'],
            ['id_ung_vien' => 2, 'id_chuc_vu' => 5, 'ngay_ung_tuyen' => '2024-10-06', 'trang_thai' => 2, 'trang_thai_cu' => '1', 'ghi_chu' => 'Đang chờ phỏng vấn vòng 1'],
            ['id_ung_vien' => 3, 'id_chuc_vu' => 5, 'ngay_ung_tuyen' => '2024-11-02', 'trang_thai' => 1, 'trang_thai_cu' => null, 'ghi_chu' => 'Mới nộp, chờ xét hồ sơ'],
            ['id_ung_vien' => 4, 'id_chuc_vu' => 5, 'ngay_ung_tuyen' => '2024-11-03', 'trang_thai' => 2, 'trang_thai_cu' => '1', 'ghi_chu' => null],
            ['id_ung_vien' => 5, 'id_chuc_vu' => 5, 'ngay_ung_tuyen' => '2024-12-01', 'trang_thai' => 1, 'trang_thai_cu' => null, 'ghi_chu' => 'Hồ sơ đẹp'],
            ['id_ung_vien' => 6, 'id_chuc_vu' => 5, 'ngay_ung_tuyen' => '2024-12-02', 'trang_thai' => 4, 'trang_thai_cu' => '3', 'ghi_chu' => 'Trúng tuyển — đang ký hợp đồng'],
            ['id_ung_vien' => 7, 'id_chuc_vu' => 7, 'ngay_ung_tuyen' => '2025-01-07', 'trang_thai' => 1, 'trang_thai_cu' => null, 'ghi_chu' => 'Thực tập sinh tiềm năng'],
            ['id_ung_vien' => 8, 'id_chuc_vu' => 7, 'ngay_ung_tuyen' => '2025-01-08', 'trang_thai' => 2, 'trang_thai_cu' => '1', 'ghi_chu' => null],
        ];

        foreach ($data as $item) {
            DB::table('ho_so_ung_tuyens')->insertOrIgnore(array_merge($item, [
                'ngay_cap_nhat' => now(),
                'created_at'    => now(),
                'updated_at'    => now(),
            ]));
        }
    }
}
