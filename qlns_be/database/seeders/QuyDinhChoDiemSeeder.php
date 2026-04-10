<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class QuyDinhChoDiemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run(): void
    {
        $data = [
            ['ma_so' => 101, 'noi_dung' => 'Hoàn thành dự án trước deadline',         'so_diem' => 10.0, 'loai_diem' => 'thuong', 'tinh_trang' => 'active', 'ghi_chu' => 'Áp dụng khi hoàn thành sớm hơn ít nhất 2 ngày'],
            ['ma_so' => 102, 'noi_dung' => 'Đề xuất sáng kiến được áp dụng',          'so_diem' =>  5.0, 'loai_diem' => 'thuong', 'tinh_trang' => 'active', 'ghi_chu' => null],
            ['ma_so' => 103, 'noi_dung' => 'Đạt 100% KPI tháng',                      'so_diem' => 20.0, 'loai_diem' => 'thuong', 'tinh_trang' => 'active', 'ghi_chu' => 'Thưởng cuối tháng'],
            ['ma_so' => 201, 'noi_dung' => 'Đi trễ giờ làm không có lý do',           'so_diem' =>  2.0, 'loai_diem' => 'phat',   'tinh_trang' => 'active', 'ghi_chu' => 'Lần 1: cảnh cáo; lần 2 trở đi: trừ điểm'],
            ['ma_so' => 202, 'noi_dung' => 'Bỏ họp bắt buộc không báo trước',         'so_diem' =>  5.0, 'loai_diem' => 'phat',   'tinh_trang' => 'active', 'ghi_chu' => null],
            ['ma_so' => 203, 'noi_dung' => 'Nộp báo cáo trễ hạn',                    'so_diem' =>  3.0, 'loai_diem' => 'phat',   'tinh_trang' => 'active', 'ghi_chu' => 'Mỗi ngày trễ trừ 3 điểm'],
            ['ma_so' => 204, 'noi_dung' => 'Vi phạm quy định bảo mật thông tin',      'so_diem' => 30.0, 'loai_diem' => 'phat',   'tinh_trang' => 'active', 'ghi_chu' => 'Vi phạm nghiêm trọng'],
            ['ma_so' => 104, 'noi_dung' => 'Hỗ trợ đồng nghiệp vượt quá phạm vi',    'so_diem' =>  3.0, 'loai_diem' => 'thuong', 'tinh_trang' => 'active', 'ghi_chu' => 'Được trưởng phòng xác nhận'],
        ];

        foreach ($data as $item) {
            DB::table('quy_dinh_cho_diems')->insertOrIgnore(array_merge($item, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
