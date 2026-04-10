<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DanhGiaUngVienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['id_ho_so_ung_tuyen' => 1, 'id_tieu_chi' => 1, 'diem_danh_gia' => 25, 'id_nhan_vien_danh_gia' => 2],
            ['id_ho_so_ung_tuyen' => 1, 'id_tieu_chi' => 2, 'diem_danh_gia' => 20, 'id_nhan_vien_danh_gia' => 2],
            ['id_ho_so_ung_tuyen' => 1, 'id_tieu_chi' => 3, 'diem_danh_gia' => 13, 'id_nhan_vien_danh_gia' => 2],
            ['id_ho_so_ung_tuyen' => 2, 'id_tieu_chi' => 1, 'diem_danh_gia' => 22, 'id_nhan_vien_danh_gia' => 2],
            ['id_ho_so_ung_tuyen' => 2, 'id_tieu_chi' => 4, 'diem_danh_gia' => 12, 'id_nhan_vien_danh_gia' => 2],
            ['id_ho_so_ung_tuyen' => 6, 'id_tieu_chi' => 1, 'diem_danh_gia' => 28, 'id_nhan_vien_danh_gia' => 3],
            ['id_ho_so_ung_tuyen' => 6, 'id_tieu_chi' => 7, 'diem_danh_gia' => 87, 'id_nhan_vien_danh_gia' => 3],
            ['id_ho_so_ung_tuyen' => 6, 'id_tieu_chi' => 8, 'diem_danh_gia' => 90, 'id_nhan_vien_danh_gia' => 1],
        ];

        foreach ($data as $item) {
            DB::table('danh_gia_ung_viens')->insertOrIgnore(array_merge($item, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
