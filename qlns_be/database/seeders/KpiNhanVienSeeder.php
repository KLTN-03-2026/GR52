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
            ['id_nhan_vien' => 3, 'id_tieu_chi' => 1, 'diem_duoc_cham' => 28.0, 'id_nhan_vien_danh_gia' => 2, 'ngay_danh_gia' => '2024-10-31'],
            ['id_nhan_vien' => 3, 'id_tieu_chi' => 2, 'diem_duoc_cham' => 22.0, 'id_nhan_vien_danh_gia' => 2, 'ngay_danh_gia' => '2024-10-31'],
            ['id_nhan_vien' => 3, 'id_tieu_chi' => 3, 'diem_duoc_cham' => 19.0, 'id_nhan_vien_danh_gia' => 2, 'ngay_danh_gia' => '2024-10-31'],
            ['id_nhan_vien' => 5, 'id_tieu_chi' => 1, 'diem_duoc_cham' => 25.0, 'id_nhan_vien_danh_gia' => 2, 'ngay_danh_gia' => '2024-10-31'],
            ['id_nhan_vien' => 5, 'id_tieu_chi' => 3, 'diem_duoc_cham' => 17.0, 'id_nhan_vien_danh_gia' => 2, 'ngay_danh_gia' => '2024-10-31'],
            ['id_nhan_vien' => 6, 'id_tieu_chi' => 1, 'diem_duoc_cham' => 30.0, 'id_nhan_vien_danh_gia' => 3, 'ngay_danh_gia' => '2024-10-31'],
            ['id_nhan_vien' => 6, 'id_tieu_chi' => 6, 'diem_duoc_cham' =>  4.5, 'id_nhan_vien_danh_gia' => 3, 'ngay_danh_gia' => '2024-10-31'],
            ['id_nhan_vien' => 7, 'id_tieu_chi' => 8, 'diem_duoc_cham' => 42.0, 'id_nhan_vien_danh_gia' => 2, 'ngay_danh_gia' => '2024-10-31'],
        ];

        foreach ($data as $item) {
            DB::table('kpi_nhan_viens')->insertOrIgnore(array_merge($item, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
