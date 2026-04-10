<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class PhanQuyenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['id_nhanvien' => 1, 'ten_chuc_nang' => 'quan_ly_nhan_vien'],
            ['id_nhanvien' => 1, 'ten_chuc_nang' => 'quan_ly_tuyen_dung'],
            ['id_nhanvien' => 1, 'ten_chuc_nang' => 'bao_cao_thong_ke'],
            ['id_nhanvien' => 1, 'ten_chuc_nang' => 'phan_quyen_he_thong'],
            ['id_nhanvien' => 2, 'ten_chuc_nang' => 'quan_ly_nhan_vien'],
            ['id_nhanvien' => 2, 'ten_chuc_nang' => 'quan_ly_tuyen_dung'],
            ['id_nhanvien' => 3, 'ten_chuc_nang' => 'quan_ly_cham_cong'],
            ['id_nhanvien' => 4, 'ten_chuc_nang' => 'xem_bao_cao'],
        ];

        foreach ($data as $item) {
            DB::table('phan_quyens')->insertOrIgnore(array_merge($item, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
