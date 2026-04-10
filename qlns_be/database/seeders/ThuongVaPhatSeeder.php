<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ThuongVaPhatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['id_nhan_vien' => 3, 'id_nhan_vien_cho_diem' => 2, 'id_quy_dinh' => 3, 'diem' => 20.0, 'ly_do' => 'Đạt 100% KPI tháng 10/2024',                    'ngay' => '2024-10-31'],
            ['id_nhan_vien' => 6, 'id_nhan_vien_cho_diem' => 3, 'id_quy_dinh' => 1, 'diem' => 10.0, 'ly_do' => 'Hoàn thành module tuyển dụng trước 3 ngày',      'ngay' => '2024-11-05'],
            ['id_nhan_vien' => 5, 'id_nhan_vien_cho_diem' => 2, 'id_quy_dinh' => 4, 'diem' =>  2.0, 'ly_do' => 'Đến muộn 15 phút không báo cáo ngày 04/11',      'ngay' => '2024-11-04'],
            ['id_nhan_vien' => 7, 'id_nhan_vien_cho_diem' => 2, 'id_quy_dinh' => 3, 'diem' => 20.0, 'ly_do' => 'Đạt 110% doanh số tháng 10/2024',               'ngay' => '2024-10-31'],
            ['id_nhan_vien' => 4, 'id_nhan_vien_cho_diem' => 2, 'id_quy_dinh' => 6, 'diem' =>  3.0, 'ly_do' => 'Nộp báo cáo tài chính tháng 10 trễ 1 ngày',     'ngay' => '2024-11-02'],
            ['id_nhan_vien' => 6, 'id_nhan_vien_cho_diem' => 3, 'id_quy_dinh' => 8, 'diem' =>  3.0, 'ly_do' => 'Hỗ trợ team QA debug ngoài phạm vi công việc',   'ngay' => '2024-11-10'],
            ['id_nhan_vien' => 8, 'id_nhan_vien_cho_diem' => 3, 'id_quy_dinh' => 2, 'diem' =>  5.0, 'ly_do' => 'Đề xuất script tự động hoá build pipeline',       'ngay' => '2024-11-15'],
            ['id_nhan_vien' => 5, 'id_nhan_vien_cho_diem' => 2, 'id_quy_dinh' => 5, 'diem' =>  5.0, 'ly_do' => 'Vắng họp all-hands tháng 11 không báo trước',    'ngay' => '2024-11-20'],
        ];

        foreach ($data as $item) {
            DB::table('thuong_va_phats')->insertOrIgnore(array_merge($item, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
