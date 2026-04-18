<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChamCongSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['id_nhan_vien' => 1, 'ngay_lam_viec' => '2024-11-01', 'ca_lam' => 'sang',  'gio_vao' => '08:00:00', 'gio_ra' => '12:00:00', 'so_gio_lam' => 4.0,  'trang_thai' => 2, 'ghi_chu_admin' => 'Xác nhận tự động'],
            ['id_nhan_vien' => 1, 'ngay_lam_viec' => '2024-11-02', 'ca_lam' => 'sang',  'gio_vao' => '08:05:00', 'gio_ra' => '12:10:00', 'so_gio_lam' => 4.08, 'trang_thai' => 2],
            ['id_nhan_vien' => 2, 'ngay_lam_viec' => '2024-11-01', 'ca_lam' => 'sang',  'gio_vao' => '07:55:00', 'gio_ra' => '12:05:00', 'so_gio_lam' => 4.17, 'trang_thai' => 2],
            ['id_nhan_vien' => 3, 'ngay_lam_viec' => '2024-11-01', 'ca_lam' => 'chieu', 'gio_vao' => '13:00:00', 'gio_ra' => '17:00:00', 'so_gio_lam' => 4.0,  'trang_thai' => 2],
            ['id_nhan_vien' => 4, 'ngay_lam_viec' => '2024-11-03', 'ca_lam' => 'sang',  'gio_vao' => '08:00:00', 'gio_ra' => '12:00:00', 'so_gio_lam' => 4.0,  'trang_thai' => 1, 'ghi_chu_nhan_vien' => 'Làm thêm 5p'],
            ['id_nhan_vien' => 5, 'ngay_lam_viec' => '2024-11-04', 'ca_lam' => 'toi',   'gio_vao' => '18:00:00', 'gio_ra' => '22:00:00', 'so_gio_lam' => 4.0,  'trang_thai' => 3, 'ghi_chu_admin' => 'Ảnh check-in mờ'],
            ['id_nhan_vien' => 6, 'ngay_lam_viec' => '2024-11-05', 'ca_lam' => 'sang',  'gio_vao' => '08:00:00', 'gio_ra' => null,       'so_gio_lam' => null, 'trang_thai' => 0],
            ['id_nhan_vien' => 7, 'ngay_lam_viec' => '2024-11-05', 'ca_lam' => 'chieu', 'gio_vao' => '13:00:00', 'gio_ra' => '17:15:00', 'so_gio_lam' => 4.25, 'trang_thai' => 2],
        ];

        foreach ($data as $item) {
            DB::table('cham_congs')->insertOrIgnore(array_merge($item, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
