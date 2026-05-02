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
            // ═══ THÁNG 4/2026 (Current month - hiển thị trên dashboard) ═══
            ['id_nhan_vien' => 1, 'ngay_lam_viec' => '2026-04-01', 'ca_lam' => 'sang',  'gio_vao' => '08:00:00', 'gio_ra' => '12:00:00', 'so_gio_lam' => 4.0,  'trang_thai' => 2, 'ghi_chu_admin' => 'Xác nhận tự động'],
            ['id_nhan_vien' => 1, 'ngay_lam_viec' => '2026-04-02', 'ca_lam' => 'sang',  'gio_vao' => '08:05:00', 'gio_ra' => '12:10:00', 'so_gio_lam' => 4.08, 'trang_thai' => 2],
            ['id_nhan_vien' => 1, 'ngay_lam_viec' => '2026-04-03', 'ca_lam' => 'chieu', 'gio_vao' => '13:00:00', 'gio_ra' => '17:30:00', 'so_gio_lam' => 4.5,  'trang_thai' => 2],
            ['id_nhan_vien' => 1, 'ngay_lam_viec' => '2026-04-04', 'ca_lam' => 'sang',  'gio_vao' => '08:00:00', 'gio_ra' => '12:00:00', 'so_gio_lam' => 4.0,  'trang_thai' => 2],
            ['id_nhan_vien' => 1, 'ngay_lam_viec' => '2026-04-07', 'ca_lam' => 'sang',  'gio_vao' => '08:00:00', 'gio_ra' => '12:00:00', 'so_gio_lam' => 4.0,  'trang_thai' => 2],

            ['id_nhan_vien' => 2, 'ngay_lam_viec' => '2026-04-01', 'ca_lam' => 'sang',  'gio_vao' => '07:55:00', 'gio_ra' => '12:05:00', 'so_gio_lam' => 4.17, 'trang_thai' => 2],
            ['id_nhan_vien' => 2, 'ngay_lam_viec' => '2026-04-02', 'ca_lam' => 'chieu', 'gio_vao' => '13:00:00', 'gio_ra' => '17:00:00', 'so_gio_lam' => 4.0,  'trang_thai' => 2],
            ['id_nhan_vien' => 2, 'ngay_lam_viec' => '2026-04-03', 'ca_lam' => 'sang',  'gio_vao' => '08:10:00', 'gio_ra' => '12:20:00', 'so_gio_lam' => 4.17, 'trang_thai' => 2],

            ['id_nhan_vien' => 3, 'ngay_lam_viec' => '2026-04-01', 'ca_lam' => 'chieu', 'gio_vao' => '13:00:00', 'gio_ra' => '17:00:00', 'so_gio_lam' => 4.0,  'trang_thai' => 2],
            ['id_nhan_vien' => 3, 'ngay_lam_viec' => '2026-04-02', 'ca_lam' => 'sang',  'gio_vao' => '08:00:00', 'gio_ra' => '12:00:00', 'so_gio_lam' => 4.0,  'trang_thai' => 2],
            ['id_nhan_vien' => 3, 'ngay_lam_viec' => '2026-04-07', 'ca_lam' => 'toi',   'gio_vao' => '18:00:00', 'gio_ra' => '22:00:00', 'so_gio_lam' => 4.0,  'trang_thai' => 2],

            ['id_nhan_vien' => 4, 'ngay_lam_viec' => '2026-04-03', 'ca_lam' => 'sang',  'gio_vao' => '08:00:00', 'gio_ra' => '12:00:00', 'so_gio_lam' => 4.0,  'trang_thai' => 1, 'ghi_chu_nhan_vien' => 'Đang chấm công'],
            ['id_nhan_vien' => 4, 'ngay_lam_viec' => '2026-04-04', 'ca_lam' => 'chieu', 'gio_vao' => '13:00:00', 'gio_ra' => '17:15:00', 'so_gio_lam' => 4.25, 'trang_thai' => 2],

            ['id_nhan_vien' => 5, 'ngay_lam_viec' => '2026-04-04', 'ca_lam' => 'toi',   'gio_vao' => '18:00:00', 'gio_ra' => '22:30:00', 'so_gio_lam' => 4.5,  'trang_thai' => 2],
            ['id_nhan_vien' => 5, 'ngay_lam_viec' => '2026-04-07', 'ca_lam' => 'sang',  'gio_vao' => '08:00:00', 'gio_ra' => '12:00:00', 'so_gio_lam' => 4.0,  'trang_thai' => 2],

            // ═══ THÁNG 1/2026 (Old data) ═══
            ['id_nhan_vien' => 1, 'ngay_lam_viec' => '2026-01-01', 'ca_lam' => 'sang',  'gio_vao' => '08:00:00', 'gio_ra' => '12:00:00', 'so_gio_lam' => 4.0,  'trang_thai' => 2, 'ghi_chu_admin' => 'Xác nhận tự động'],
            ['id_nhan_vien' => 1, 'ngay_lam_viec' => '2026-01-02', 'ca_lam' => 'sang',  'gio_vao' => '08:05:00', 'gio_ra' => '12:10:00', 'so_gio_lam' => 4.08, 'trang_thai' => 2],
            ['id_nhan_vien' => 2, 'ngay_lam_viec' => '2026-01-01', 'ca_lam' => 'sang',  'gio_vao' => '07:55:00', 'gio_ra' => '12:05:00', 'so_gio_lam' => 4.17, 'trang_thai' => 2],
            ['id_nhan_vien' => 3, 'ngay_lam_viec' => '2026-01-01', 'ca_lam' => 'chieu', 'gio_vao' => '13:00:00', 'gio_ra' => '17:00:00', 'so_gio_lam' => 4.0,  'trang_thai' => 2],
        ];

        foreach ($data as $item) {
            DB::table('cham_congs')->insertOrIgnore(array_merge($item, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
