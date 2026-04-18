<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UngVienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['ho_ten' => 'Nguyễn Thị Mai',   'email' => 'mai.nguyen@gmail.com',    'so_dien_thoai' => '0912001001', 'tinh_trang' => 1, 'ghi_chu' => 'Ứng viên tiềm năng cho vị trí Frontend'],
            ['ho_ten' => 'Trần Văn Hùng',    'email' => 'hung.tran@gmail.com',     'so_dien_thoai' => '0912001002', 'tinh_trang' => 1, 'ghi_chu' => null],
            ['ho_ten' => 'Lê Thị Phương',    'email' => 'phuong.le@yahoo.com',     'so_dien_thoai' => '0912001003', 'tinh_trang' => 1, 'ghi_chu' => 'Có kinh nghiệm 3 năm Marketing'],
            ['ho_ten' => 'Phạm Minh Tuấn',   'email' => 'tuan.pham@outlook.com',   'so_dien_thoai' => '0912001004', 'tinh_trang' => 1, 'ghi_chu' => null],
            ['ho_ten' => 'Võ Thị Lan',       'email' => 'lan.vo@gmail.com',        'so_dien_thoai' => '0912001005', 'tinh_trang' => 1, 'ghi_chu' => 'Kế toán 5 năm kinh nghiệm'],
            ['ho_ten' => 'Đặng Quốc Bảo',    'email' => 'bao.dang@gmail.com',      'so_dien_thoai' => '0912001006', 'tinh_trang' => 1, 'ghi_chu' => null],
            ['ho_ten' => 'Bùi Thị Ngọc',     'email' => 'ngoc.bui@gmail.com',      'so_dien_thoai' => '0912001007', 'tinh_trang' => 0, 'ghi_chu' => 'Đã trúng tuyển — chuyển thành nhân viên'],
            ['ho_ten' => 'Hoàng Văn Thắng',  'email' => 'thang.hoang@gmail.com',   'so_dien_thoai' => '0912001008', 'tinh_trang' => 1, 'ghi_chu' => 'Sinh viên năm 4 CNTT'],
        ];

        foreach ($data as $item) {
            DB::table('ung_viens')->insertOrIgnore(array_merge($item, [
                'file_cv'    => 'cv/' . \Illuminate\Support\Str::slug($item['ho_ten']) . '.pdf',
                'password'   => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
