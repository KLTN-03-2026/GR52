<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PhongBanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['ten_phong_ban' => 'Ban Giám đốc',          'id_phong_ban_cha' => null, 'tinh_trang' => 1],
            ['ten_phong_ban' => 'Phòng Nhân sự',         'id_phong_ban_cha' => null, 'tinh_trang' => 1],
            ['ten_phong_ban' => 'Phòng Công nghệ thông tin', 'id_phong_ban_cha' => null, 'tinh_trang' => 1],
            ['ten_phong_ban' => 'Phòng Kế toán',         'id_phong_ban_cha' => null, 'tinh_trang' => 1],
            ['ten_phong_ban' => 'Phòng Marketing',       'id_phong_ban_cha' => null, 'tinh_trang' => 1],
            ['ten_phong_ban' => 'Phòng Kinh doanh',      'id_phong_ban_cha' => null, 'tinh_trang' => 1],
            ['ten_phong_ban' => 'Tổ Hạ tầng',            'id_phong_ban_cha' => 3,    'tinh_trang' => 1],
            ['ten_phong_ban' => 'Tổ Phát triển phần mềm','id_phong_ban_cha' => 3,    'tinh_trang' => 1],
        ];

        foreach ($data as $item) {
            DB::table('phong_bans')->insertOrIgnore(array_merge($item, [
                'id_truong_phong' => null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]));
        }
    }
}
