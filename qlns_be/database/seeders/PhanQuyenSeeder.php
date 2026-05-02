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
            ['id_nhanvien' => 1, 'ten_chuc_nang' => 'system.admin'],
            ['id_nhanvien' => 2, 'ten_chuc_nang' => 'system.hr'],     // Trần Thị Bình - HR Manager
            ['id_nhanvien' => 3, 'ten_chuc_nang' => 'system.admin'],  // Lê Hoàng Cường - Admin
        ];

        foreach ($data as $item) {
            DB::table('phan_quyens')->insertOrIgnore(array_merge($item, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
