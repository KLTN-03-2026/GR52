<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChiTietHopDongSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
    {
        $data = [
            ['id_nhan_vien' => 1, 'id_loai_hop_dong' => 4, 'ngay_ky' => '2020-01-01', 'ngay_bat_dau' => '2020-01-01', 'ngay_ket_thuc' => null],
            ['id_nhan_vien' => 2, 'id_loai_hop_dong' => 4, 'ngay_ky' => '2019-06-01', 'ngay_bat_dau' => '2019-06-01', 'ngay_ket_thuc' => null],
            ['id_nhan_vien' => 3, 'id_loai_hop_dong' => 3, 'ngay_ky' => '2022-03-15', 'ngay_bat_dau' => '2022-03-15', 'ngay_ket_thuc' => '2024-03-15'],
            ['id_nhan_vien' => 4, 'id_loai_hop_dong' => 2, 'ngay_ky' => '2023-01-10', 'ngay_bat_dau' => '2023-01-10', 'ngay_ket_thuc' => '2024-01-10'],
            ['id_nhan_vien' => 5, 'id_loai_hop_dong' => 1, 'ngay_ky' => '2024-02-01', 'ngay_bat_dau' => '2024-02-01', 'ngay_ket_thuc' => '2024-04-01'],
            ['id_nhan_vien' => 6, 'id_loai_hop_dong' => 2, 'ngay_ky' => '2023-08-01', 'ngay_bat_dau' => '2023-08-01', 'ngay_ket_thuc' => '2024-08-01'],
            ['id_nhan_vien' => 7, 'id_loai_hop_dong' => 3, 'ngay_ky' => '2022-09-01', 'ngay_bat_dau' => '2022-09-01', 'ngay_ket_thuc' => '2024-09-01'],
            ['id_nhan_vien' => 8, 'id_loai_hop_dong' => 7, 'ngay_ky' => '2024-06-01', 'ngay_bat_dau' => '2024-06-01', 'ngay_ket_thuc' => '2024-12-01'],
        ];

        foreach ($data as $item) {
            DB::table('chi_tiet_hop_dongs')->insertOrIgnore(array_merge($item, [
                'noi_dung'   => 'Nội dung chi tiết hợp đồng theo mẫu số ' . $item['id_loai_hop_dong'],
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
