<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChucVuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['ten_chuc_vu' => 'Giám đốc',              'tinh_trang' => 1],
            ['ten_chuc_vu' => 'Phó Giám đốc',          'tinh_trang' => 1],
            ['ten_chuc_vu' => 'Trưởng phòng',          'tinh_trang' => 1],
            ['ten_chuc_vu' => 'Phó phòng',             'tinh_trang' => 1],
            ['ten_chuc_vu' => 'Nhân viên chính thức',  'tinh_trang' => 1],
            ['ten_chuc_vu' => 'Nhân viên thử việc',    'tinh_trang' => 1],
            ['ten_chuc_vu' => 'Thực tập sinh',         'tinh_trang' => 1],
            ['ten_chuc_vu' => 'Chuyên viên cao cấp',   'tinh_trang' => 0],
        ];

        foreach ($data as $item) {
            DB::table('chuc_vus')->insertOrIgnore(array_merge($item, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
