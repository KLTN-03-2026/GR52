<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class LoaiHopDongSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['ten_hop_dong' => 'Hợp đồng thử việc',            'tinh_trang' => 1, 'noi_dung' => 'Hợp đồng thử việc 2 tháng theo quy định lao động.'],
            ['ten_hop_dong' => 'Hợp đồng xác định thời hạn 1 năm', 'tinh_trang' => 1, 'noi_dung' => 'Hợp đồng lao động có thời hạn 12 tháng.'],
            ['ten_hop_dong' => 'Hợp đồng xác định thời hạn 2 năm', 'tinh_trang' => 1, 'noi_dung' => 'Hợp đồng lao động có thời hạn 24 tháng.'],
            ['ten_hop_dong' => 'Hợp đồng không xác định thời hạn', 'tinh_trang' => 1, 'noi_dung' => 'Hợp đồng lao động không xác định thời hạn — biên chế chính thức.'],
            ['ten_hop_dong' => 'Hợp đồng thời vụ',             'tinh_trang' => 1, 'noi_dung' => 'Hợp đồng theo mùa vụ, không quá 3 tháng.'],
            ['ten_hop_dong' => 'Hợp đồng khoán việc',          'tinh_trang' => 1, 'noi_dung' => 'Hợp đồng thanh toán theo kết quả công việc hoàn thành.'],
            ['ten_hop_dong' => 'Hợp đồng thực tập',            'tinh_trang' => 1, 'noi_dung' => 'Hợp đồng dành cho sinh viên thực tập tại công ty.'],
            ['ten_hop_dong' => 'Hợp đồng cộng tác viên',       'tinh_trang' => 0, 'noi_dung' => 'Hợp đồng dịch vụ cho cộng tác viên bên ngoài — đã ngưng áp dụng.'],
        ];

        foreach ($data as $item) {
            DB::table('loai_hop_dongs')->insertOrIgnore(array_merge($item, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
