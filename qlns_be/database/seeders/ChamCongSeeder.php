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
            ['id_nhan_vien' => 1, 'ngay_lam_viec' => '2024-11-01 08:00:00', 'ca_lam' => 'sang'],
            ['id_nhan_vien' => 1, 'ngay_lam_viec' => '2024-11-02 08:00:00', 'ca_lam' => 'sang'],
            ['id_nhan_vien' => 2, 'ngay_lam_viec' => '2024-11-01 08:00:00', 'ca_lam' => 'sang'],
            ['id_nhan_vien' => 3, 'ngay_lam_viec' => '2024-11-01 13:00:00', 'ca_lam' => 'chieu'],
            ['id_nhan_vien' => 4, 'ngay_lam_viec' => '2024-11-03 08:00:00', 'ca_lam' => 'sang'],
            ['id_nhan_vien' => 5, 'ngay_lam_viec' => '2024-11-04 18:00:00', 'ca_lam' => 'toi'],
            ['id_nhan_vien' => 6, 'ngay_lam_viec' => '2024-11-05 08:00:00', 'ca_lam' => 'sang'],
            ['id_nhan_vien' => 7, 'ngay_lam_viec' => '2024-11-05 13:00:00', 'ca_lam' => 'chieu'],
        ];

        foreach ($data as $item) {
            DB::table('cham_congs')->insertOrIgnore(array_merge($item, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
